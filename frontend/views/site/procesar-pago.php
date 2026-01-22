<style type="text/css">

    .wrap-user {
        min-height: 83.2%;
    }

    .texto-espera {
        font-size: 28px;
        text-align: center;
    }

    .spinner {
        margin:  40px auto;
        border: 4px solid rgba(0, 0, 0, .1);
        border-left-color: transparent;
        border-radius: 50%;
    }
    .spinner {
        border: 4px solid rgba(0, 0, 0, .1);
        border-left-color: transparent;
        width: 36px;
        height: 36px;
    }

    .spinner {
        border: 4px solid rgba(0, 0, 0, .1);
        border-left-color: transparent;
        width: 36px;
        height: 36px;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }    
</style>


<div class="site-procesar-pago">
    <div class="container-page">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <form name="realizarPago" id="realizarPago" action="<?php echo $url_tpv; ?>" method="post">
                    <input type='hidden' name='Ds_SignatureVersion' value='<?php echo $version; ?>'> 
                    <input type='hidden' name='Ds_MerchantParameters' value='<?php echo $params; ?>'> 
                    <input type='hidden' name='Ds_Signature' value='<?php echo $signature; ?>'> 
                </form>
                <?php if (!empty($adjustmentAmountLabel)) : ?>
                    <div style="margin-bottom: 20px; text-align: center;">
                        <p style="font-size: 20px; margin-bottom: 8px;">
                            Se está generando un nuevo pago por la diferencia: <strong><?= $adjustmentAmountLabel ?> €</strong>
                        </p>
                        <?php if (!empty($adjustmentItems)) : ?>
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                <?php foreach ($adjustmentItems as $item) : ?>
                                    <li style="font-size: 16px; margin-bottom: 4px;"><?= $item ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class='spinner'></div>
                <p class="texto-espera">Un momento por favor...</p>
            </div>
        </div>
    </div>
</div>

<?php

$this->registerJs("

    $(document).ready(function(){
        setTimeout($('#realizarPago').submit(),9000);
        
    });  

") ?>      
  

    
