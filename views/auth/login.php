<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php include_once __DIR__ . "/../templates/alertas.php"; ?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Tu email" name="email" />
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" placeholder="Tu contraseña" name="password" />
    </div>
    <input class="boton" type="submit" value="Iniciar Sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
    <a href="/olvide-password">¿Olvidaste tu contraseña?</a>
</div>