<h1 class="nombre-pagina">Panel de Administración</h1>

<?php include_once __DIR__ . "/../templates/barra.php"; ?>

<h2>Buscar Citas</h2>

<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
        </div>
    </form>
</div>

<?php if (count($citas) === 0) {
    echo "<h2>No hay citas en esta fecha</h2>";
} ?>

<div id="citas-admin">
    <ul class="citas">
        <?php $idCita = ""; ?>
        <?php foreach ($citas as $indice => $cita) { ?>

            <?php if ($idCita !== $cita->id) { ?>
                <?php $totalCita = 0; ?>
                <li>
                    <?php $idCita = $cita->id; ?>
                    <p>ID: <span><?php echo $cita->id; ?></span></p>
                    <p>Hora: <span><?php echo $cita->hora; ?></span></p>
                    <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
                    <p>Email: <span><?php echo $cita->email; ?></span></p>
                    <p>Teléfono: <span><?php echo $cita->telefono; ?></span></p>
                    <h3>Servicios</h3>

                <?php } ?>

                <?php
                $citaActual = $cita->id;
                $citaProxima = $citas[$indice + 1]->id ?? 0;
                $totalCita += $cita->precio;
                ?>

                <p class="servicio"><?php echo $cita->servicio . ": $" . $cita->precio; ?></p>

                <?php if (ultimaPosicion($citaActual, $citaProxima)) { ?>
                    <p class="total">Total: <span><?php echo "$$totalCita"; ?></span></p>

                    <form action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $citaActual; ?>">
                        <input type="submit" class="boton-eliminar" value="Eliminar">
                    </form>

                <?php } ?>
            <?php } ?>

    </ul>
</div>

<?php
$script = "
    <script src='build/js/buscador.js'></script>
"
?>