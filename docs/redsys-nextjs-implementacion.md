# Guía práctica: integrar pagos con Redsys en un proyecto Next.js

## 1) Cómo funciona Redsys (visión completa de extremo a extremo)

Redsys usa un flujo **hosted payment page**: tú preparas una petición firmada, rediriges al usuario al TPV de Redsys, y luego validas la respuesta firmada para confirmar el estado del pago.

Flujo típico:

1. **Tu backend crea la operación**
   - Generas los parámetros `Ds_MerchantParameters` con datos del pedido (importe, moneda, número de pedido, URL de notificación, etc.).
   - Calculas `Ds_Signature` con tu clave secreta del comercio.
   - Envías al frontend (o renderizas directamente) un formulario POST hacia Redsys.

2. **El cliente paga en Redsys**
   - El usuario introduce tarjeta en la pasarela de Redsys (no en tu web).
   - Esto reduce alcance PCI porque no tocas PAN/CVV en tu infraestructura.

3. **Redsys devuelve resultado por dos vías**
   - **Notificación servidor-a-servidor** (`merchantURL`, también llamada notificación online): la más importante para confirmar pago real.
   - **Redirección del navegador** a tus URLs de éxito/error (`urlOK`, `urlKO`) para UX del usuario.

4. **Tu backend valida firma de la respuesta**
   - Decodificas `Ds_MerchantParameters` recibido.
   - Recalculás firma y comparas con `Ds_Signature`.
   - Compruebas `Ds_Response` (código de autorización/denegación).
   - Actualizas pedido en base de datos con control de idempotencia.

5. **Tu frontend consulta estado**
   - Nunca des por pagado solo con `urlOK`.
   - Muestra estado real leyendo tu backend (que ya validó notificación).

---

## 2) Conceptos y campos que debes dominar

- **FUC / código de comercio** (`Ds_Merchant_MerchantCode`): te lo da banco/Redsys.
- **Terminal** (`Ds_Merchant_Terminal`): normalmente `1`.
- **Moneda** (`Ds_Merchant_Currency`): por ejemplo `978` (EUR).
- **Transacción** (`Ds_Merchant_TransactionType`): normalmente `0` para autorización/compra.
- **Pedido** (`Ds_Merchant_Order`): identificador único por operación (muy importante).
- **Importe** (`Ds_Merchant_Amount`): en **minor units** (céntimos). Ej: 49,95€ => `4995`.
- **Firma**:
  - Se envía en `Ds_SignatureVersion` (normalmente `HMAC_SHA256_V1`).
  - `Ds_Signature` calculada sobre `Ds_MerchantParameters`.

---

## 3) Arquitectura recomendada en Next.js

### 3.1 Separación de responsabilidades

- **Server (Route Handlers / API Routes)**
  - Crear operación Redsys (firmar petición).
  - Recibir notificación de Redsys.
  - Validar firma y actualizar pedido.
- **Client (componentes React)**
  - Iniciar checkout.
  - Mostrar pantalla de “procesando” y resultado.

### 3.2 Rutas sugeridas

- `POST /api/payments/redsys/create`
  - Recibe `orderId` interno.
  - Carga pedido desde DB, valida importe.
  - Genera formulario/token para Redsys.
- `POST /api/payments/redsys/notify`
  - Endpoint para `Ds_Merchant_MerchantURL`.
  - Valida firma + estado.
  - Marca pedido `paid/failed`.
- `GET /checkout/result?order=...`
  - Página final de usuario.
  - Consulta backend para estado real.

---

## 4) Implementación paso a paso (similar a producción real)

## Paso 1: Variables de entorno

Define:

- `REDSYS_MERCHANT_CODE`
- `REDSYS_TERMINAL`
- `REDSYS_CURRENCY` (ej. `978`)
- `REDSYS_TRANSACTION_TYPE` (ej. `0`)
- `REDSYS_SECRET_KEY` (clave de firma)
- `REDSYS_URL` (entorno test o producción)
- `APP_BASE_URL` (para `merchantURL`, `urlOK`, `urlKO`)

Buenas prácticas:
- Secretos solo en servidor.
- Separar `.env.test` vs `.env.production`.

## Paso 2: Crear utilidad de firma Redsys

Crea un módulo server-only:

- `buildMerchantParameters(payload)`: JSON -> Base64.
- `signMerchantParameters(order, merchantParametersB64, secretKey)`: HMAC-SHA256 (según especificación Redsys, con derivación por número de pedido).
- `verifyResponseSignature(...)`: para notificaciones y retorno.

Puntos críticos:
- Respetar codificación exacta Base64/Base64URL según documentación/librería.
- Normalizar correctamente `Ds_Signature` antes de comparar.

## Paso 3: Endpoint create

`POST /api/payments/redsys/create`:

1. Validar sesión de usuario y propiedad del pedido.
2. Verificar que el pedido está en estado “pendiente”.
3. Construir payload Redsys:
   - `Ds_Merchant_Amount`
   - `Ds_Merchant_Order`
   - `Ds_Merchant_MerchantCode`
   - `Ds_Merchant_Currency`
   - `Ds_Merchant_TransactionType`
   - `Ds_Merchant_Terminal`
   - `Ds_Merchant_MerchantURL` = `https://tu-dominio/api/payments/redsys/notify`
   - `Ds_Merchant_UrlOK` / `Ds_Merchant_UrlKO`
4. Firmar.
5. Responder datos para auto-submit del formulario.

## Paso 4: Formulario de redirección en frontend

En checkout:

- Llamas a `/api/payments/redsys/create`.
- Renderizas form `method="POST" action="REDSYS_URL"` con:
  - `Ds_SignatureVersion`
  - `Ds_MerchantParameters`
  - `Ds_Signature`
- Auto-submit con JS.

## Paso 5: Endpoint notify (la verdad del pago)

`POST /api/payments/redsys/notify`:

1. Leer `Ds_MerchantParameters` y `Ds_Signature` del body.
2. Decodificar parámetros.
3. Validar firma.
4. Revisar `Ds_Response`:
   - Habitualmente `0-99` => operación autorizada.
   - Otros => denegada/error (según tabla Redsys).
5. Idempotencia:
   - Si ya estaba `paid`, no duplicar side effects.
6. Guardar trazabilidad (response code, auth code, raw payload).
7. Responder HTTP 200 rápidamente.

## Paso 6: URL OK/KO solo para experiencia de usuario

- `urlOK` no es confirmación definitiva.
- Muestra “Estamos confirmando tu pago...” y consulta estado cada pocos segundos.
- Cuando notificación confirme, mostrar éxito.

---

## 5) Seguridad y robustez (imprescindible)

- Validar firma en **todas** las respuestas.
- Nunca confiar en importe enviado por cliente.
- `Ds_Merchant_Order` único y no predecible.
- Aplicar idempotencia en notificaciones.
- Registrar eventos y métricas de fallos.
- Timeouts/reintentos: Redsys puede reenviar notificaciones.
- HTTPS obligatorio en producción.
- Logs sin filtrar datos sensibles.

---

## 6) Errores habituales al integrar Redsys

- Importe mal escalado (euros vs céntimos).
- Pedido con formato no permitido por Redsys.
- Clave secreta incorrecta (test vs prod mezcladas).
- Firmas inválidas por usar Base64URL incorrecta.
- Marcar pedido como pagado en `urlOK` sin notificación.

---

## 7) Checklist para sacar a producción

- Entorno de pruebas Redsys validado.
- Casos test: pago correcto, denegado, cancelado, timeout.
- Verificación firma en create/notify/retorno.
- Idempotencia de notificaciones validada.
- Observabilidad (logs + alertas).
- Cambio de endpoint y claves a producción.

---

## 8) Prompt reutilizable para una futura tarea (Next.js, proyecto distinto pero similar)

Copia y pega este prompt cuando quieras pedir implementación en otro repo:

```text
Quiero que implementes un flujo de pago con Redsys en este proyecto Next.js (App Router), similar al de otro proyecto que ya tengo, pero adaptado a esta base de código.

Objetivo funcional:
- Crear pago y redirigir al TPV de Redsys.
- Recibir notificación server-to-server y validar firma.
- Confirmar estado real del pedido en backend con idempotencia.
- Mostrar resultado al usuario sin confiar solo en urlOK/urlKO.

Requisitos técnicos:
1) Crear endpoints:
   - POST /api/payments/redsys/create
   - POST /api/payments/redsys/notify
   - GET /checkout/result (o equivalente)
2) Implementar utilidades server-only para:
   - Construir Ds_MerchantParameters
   - Firmar con HMAC_SHA256_V1
   - Verificar firma de respuesta
3) Añadir variables de entorno necesarias y documentación.
4) No exponer secretos al cliente.
5) Añadir control de idempotencia para notificaciones duplicadas.
6) Persistir trazabilidad mínima de la transacción (orderId, response, authCode, rawResponse resumida).
7) Añadir tests (unitarios de firma + integración básica de notify).
8) Si el proyecto tiene UI de checkout, integrar auto-submit del formulario a Redsys.
9) Incluir manejo de errores y mensajes claros para usuario.

Criterios de aceptación:
- Un pago autorizado deja el pedido en estado paid.
- Un pago denegado deja estado failed/cancelled.
- notify con firma inválida no altera pedidos y responde error controlado.
- notificaciones repetidas no duplican side effects.
- código limpio, tipado (TypeScript), y documentación breve de operación.

Entrega esperada:
- Commits atómicos.
- Resumen de arquitectura aplicada.
- Instrucciones para probar en entorno sandbox de Redsys.
```

---

## 9) Recomendación final

Si quieres, en una siguiente iteración te puedo dar una **plantilla técnica exacta para Next.js (App Router + TypeScript)** con estructura de carpetas, pseudocódigo de firma/verificación, contrato de tablas (`payments`, `payment_events`) y casos de prueba listos para implementar.
