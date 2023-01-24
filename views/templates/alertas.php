<?php
    //Doble foreach porque el arreglo $alertas tiene arreglos de errores y de alertas
    foreach($alertas as $key => $mensajes) {
        foreach($mensajes as $mensaje) {
?>
    <div class="alerta <?php echo $key;?>">
            <?php echo $mensaje;?>
    </div>
<?php
        }
    }
?>