<?php
if (empty($_GET["id"])) {
    exit;
}

$cotizacion = Cotizaciones::porId($_GET["id"]);
if (!$cotizacion) {
    exit("No existe la cotización");
}

$servicios = Cotizaciones::serviciosPorId($_GET["id"]);
$caracteristicas = Cotizaciones::caracteristicasPorId($_GET["id"]);
$tokenCSRF = Utiles::obtenerTokenCSRF();



?>
<div id="app">
    <div class="row">
        <div class="col-sm">
            <div class="row">
                <div class="col-sm-8">
                    <h3>Servicios</h3>
                    <div class="alert alert-info">
                        <p>Añada servicios que tienen un costo y precio, al final se calcularán los totales</p>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Servicio</th>
                                        <th>Costo</th>
                                        <th>Tiempo</th>
                                        <th>Editar</th>
                                        <th>Eliminar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $costoTotal = 0;
                                    $tiempoTotal = 0;
                                    ?>
                                    <?php
                                    $ivaDefault = 0.16;
                                    $ivaCal = 0;+
                                    $costo = 0;
                                    $totalConIva = 0;
                                    

                                    $iva = isset($_GET['iva']) ? floatval($_GET['iva']) : $ivaDefault;

    
                                   
                                    foreach ($servicios as $servicio) {
                                        $costoTotal += $servicio->costo;
                                        $tiempoTotal += $servicio->tiempoEnMinutos * $servicio->multiplicador;
                                        ?>
                                        <tr>
                                            <td><?php echo htmlentities($servicio->servicio) ?></td>
                                            <td class="text-nowrap">{{<?php echo htmlentities($servicio->costo) ?> |
                                                dinero}}
                                            </td>
                                            <td>
                                                {{<?php echo htmlentities($servicio->tiempoEnMinutos * $servicio->multiplicador) ?>
                                                | minutosATiempo}}
                                            </td>
                                            <td>
                                                <a
                                                        class="btn btn-warning"
                                                        href="<?php printf('%s/?p=editar_servicio_de_cotizacion&idCotizacion=%s&idServicio=%s', BASE_URL, $cotizacion->id, $servicio->id) ?>">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a
                                                        class="btn btn-danger"
                                                        href="<?php printf('%s/?p=eliminar_servicio_de_cotizacion&idCotizacion=%s&tokenCSRF=%s&idServicio=%s', BASE_URL, $cotizacion->id, $tokenCSRF, $servicio->id) ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td class="text-nowrap"><strong>{{<?php echo htmlentities($costoTotal) ?> |
                                                dinero}}</strong></td>
                                        <td><strong>{{<?php echo $tiempoTotal ?> | minutosATiempo}}</strong></td>
                                        <td colspan="2"></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h3>Agregar nuevo servicio</h3>
                    <form method="post" action="<?php echo BASE_URL ?>/?p=agregar_servicio_a_cotizacion">
                        <input type="hidden" name="idCotizacion" value="<?php echo $_GET["id"] ?>">
                        <input type="hidden" name="tokenCSRF" value="<?php echo $tokenCSRF ?>">
                        <div class="form-group">
                            <label for="servicio">Servicio</label>
                            <input autofocus name="servicio" autocomplete="off" required type="text"
                                   class="form-control" id="servicio" placeholder="Por ejemplo: Desarrollo de app">
                        </div>
                        <div class="form-group">
                            <label for="costo">Costo</label>
                            <input name="costo" autocomplete="off" id="costo" required type="number" class="form-control"
                                   id="costo" placeholder="Costo">
                        </div>
                        <div  class="form-group">
                        <label for="tiempoEnMinutos">Iva</label>
                        <select required class="form-control" name="ivaSelect" id="ivaSelect" onchange="actualizarTotal()">
                                            <option value="0">0%</option>
                                            <option value="0.08">8%</option>
                                            <option value="0.16" selected>16%</option>
                        </select> 
                        </div>
                        <div  class="form-group">
                        <label for="subTotal">Subtotal:</label>
                        <label name="subTotal" id="subTotal"> {{<?php echo htmlentities($costo) ?> | 0.00}}</label>
                        <label for="totalConIva">Total:</label>
                        <label name="totalConIva" id="totalConIva" > <?php echo ($totalConIva) ?> </label>
                        </div>
                        <div  class="form-group">
                        </div>
                        <div class="form-group">
                            <label for="tiempoEnMinutos">Tiempo</label>
                            <input name="tiempoEnMinutos" autocomplete="off" required type="number" class="form-control"
                                   id="tiempoEnMinutos" placeholder="Cantidad de tiempo que tomará el servicio">
                        </div>
                        <div class="form-group">
                            <label for="multiplicador">Especificado en</label>
                            <select required class="form-control" name="multiplicador" id="multiplicador">
                                <option value="1">Minutos</option>
                                <option value="60">Horas</option>
                                <option value="1440">Días</option>
                                <option value="10080">Semanas (7 días)</option>
                                <option value="43200">Meses (30 días)</option>
                                <option value="518400">Años (12 meses)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar</button>
                    </form>
                </div>
            </div>
            <hr>
            <div class="row">
                <?php include_once BASE_PATH . "/includes/publicidad.php" ?>                
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <h3>Características</h3>
                    <div class="alert alert-info">
                        <p>Las cosas que ayudan a describir la cotización</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Característica</th>
                                <th>Editar</th>
                                <th>Eliminar</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($caracteristicas as $caracteristica) {
                                ?>
                                <tr>
                                    <td><?php echo htmlentities($caracteristica->caracteristica); ?></td>
                                    <td>
                                        <a
                                                class="btn btn-warning"
                                                href="<?php printf('%s/?p=editar_caracteristica_de_cotizacion&idCotizacion=%s&idCaracteristica=%s', BASE_URL, $cotizacion->id, $caracteristica->id) ?>">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a
                                                class="btn btn-danger"
                                                href="<?php printf('%s/?p=eliminar_caracteristica_de_cotizacion&idCotizacion=%s&tokenCSRF=%s&idCaracteristica=%s', BASE_URL, $cotizacion->id, $tokenCSRF, $caracteristica->id) ?>">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-4">
                    <h3>Agregar característica</h3>
                    <form method="post" action="<?php echo BASE_URL ?>/?p=agregar_caracteristica_a_cotizacion">
                        <input type="hidden" name="idCotizacion" value="<?php echo $_GET["id"] ?>">
                        <input type="hidden" name="tokenCSRF" value="<?php echo $tokenCSRF ?>">
                        <div class="form-group">
                            <label for="caracteristica">Característica</label>
                            <input name="caracteristica" autocomplete="off" required type="text" class="form-control"
                                   id="caracteristica" placeholder="Algo que ayude a describir la cotización">
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        new Vue({
            el: "#app",
        });
    });
</script>
<script>
    function actualizarTotal() {
        //var ivaSeleccionado = parseFloat(selectIva.value) || parseFloat(<?php echo $ivaDefault; ?>);
       var selectIva = parseFloat(document.getElementById('ivaSelect').value); // Parsea el valor del select como un número flotante
var costo = parseFloat(document.getElementById('costo').value); // Parsea el costo como un número flotante
// Calcula el total sin IVA basado en la variable PHP $costoTotal
var totalSinIva = <?php echo $costoTotal; ?>;

// Calcula el monto del IVA
var ivaCalculado = costo * selectIva ; // Divide selectIva por 100 para obtener el porcentaje como fracción


// Calcula el nuevo total con IVA
var nuevoTotal = costo + ivaCalculado;

        // Actualizar el total en la interfaz
        document.getElementById('subTotal').innerText = costo.toLocaleString('es-MX', {
            style: 'currency',
            currency: 'MXN'
        });

          document.getElementById('totalConIva').innerText = nuevoTotal.toLocaleString('es-MX', {
            style: 'currency',
            currency: 'MXN'
        });

          // Actualizar el total de IVA calculado en la interfaz
        document.getElementById('ivaCal').innerText = ivaCalculado2.toLocaleString('es-MX', {
            style: 'currency',
            currency: 'MXN'
        });
    }
</script>