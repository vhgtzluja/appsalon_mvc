<h1 class="nombre-pagina">Restablecer password</h1>

<p class="descripcion-pagina">Ingresa tu nuevo password a continuación</p>

<?php    
    include_once __DIR__."/../templates/alertas.php";
?>

<?php if($error) return; ?>
<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Tu nuevo password">        
    </div>
    <input type="submit" class="boton" value="Guardar nuevo password">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
    <a href="/crear-cuenta">Crear una cuenta nueva</a>
</div>