<h1 class="nombre-pagina">Olvidé password</h1>
<p class="descripcion-pagina">Restablece tu password escribiendo tu e-mail a continuación</p>

<?php    
    include_once __DIR__."/../templates/alertas.php";
?>

<form class="formulario" method="POST" action="/olvide">
    <div class="campo">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" placeholder="Tu e-mail">
    </div>

    <input type="submit" value="Enviar Instrucciones" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
    <a href="/crear-cuenta">Crear una cuenta nueva</a>
</div>