-- Reservas con fecha/hora de salida dentro de la parada activa de fin de año
SELECT
  id,
  nro_reserva,
  CONCAT(fecha_salida, ' ', hora_salida) AS salida_datetime
FROM reservas
WHERE CONCAT(fecha_salida, ' ', hora_salida)
      BETWEEN '2024-12-31 19:00:00' AND '2025-01-01 11:00:00'
ORDER BY salida_datetime;
