<?php
//$cotizaciones = Cotizaciones::todas();
$resultados_por_pagina = 5;

// Obtiene la página actual, si no se especifica, se establece en 1 por defecto
$pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$cotizaciones = Cotizaciones::obtenerCotizacionesPaginadas($pagina_actual, $resultados_por_pagina);
$tokenCSRF = Utiles::obtenerTokenCSRF();

// Calcula el número total de cotizaciones
$total_cotizaciones = count(Cotizaciones::todas());

// Calcula el número total de páginas
$total_paginas = ceil($total_cotizaciones / $resultados_por_pagina);

// Verifica si se ha enviado un término de búsqueda
$termino_busqueda = isset($_GET['q']) ? $_GET['q'] : '';

// Realiza la búsqueda de cotizaciones que coincidan con el término de búsqueda
if (!empty($_GET['q'])) {
    $cotizaciones = Cotizaciones::buscarCotizaciones($termino_busqueda);
} else {
    // Si no hay término de búsqueda, muestra todas las cotizaciones
    $cotizaciones = Cotizaciones::obtenerCotizacionesPaginadas($pagina_actual, $resultados_por_pagina);
}
?>

<div class="row">
    <div class="col-sm">
        <h1>Cotizaciones</h1>
        <p>Aquí aparecen las cotizaciones</p>
    </div>
</div>

<div class="row">
    <div class="col-sm">
        <p>
            <a href="<?php echo BASE_URL ?>/?p=nueva_cotizacion" class="btn btn-success">
                <i class="fa fa-plus"></i> Nueva cotización
            </a>
        </p>
    </div>
    
    <?php include_once BASE_PATH . "/includes/publicidad.php" ?>
</div>

<div class="row">
    <div class="col-sm">
        <div class="table-responsive">
             <!-- Formulario de búsqueda -->
               <!-- Formulario de búsqueda -->
               <form method="GET" action="<?php echo BASE_URL ?>/?p=cotizaciones" class="mb-3">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Buscar..." value="<?php echo htmlentities($termino_busqueda) ?>">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </form>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Detalles y características</th>
                    <th>Imprimir</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($cotizaciones as $cotizacion) { ?>
                    <tr>
                        <td><?php echo $cotizacion->id ?></td>
                        <td><?php echo htmlentities($cotizacion->razonSocial) ?></td>
                        <td><?php echo htmlentities($cotizacion->descripcion) ?></td>
                        <td><?php echo htmlentities($cotizacion->fecha) ?></td>
                        <td>$<?php echo htmlentities(number_format($cotizacion->total, 2, '.', '.')) ?></td>

                        <td>
                            <a class="btn btn-info"
                               href="<?php echo BASE_URL ?>/?p=detalles_caracteristicas_cotizacion&id=<?php echo $cotizacion->id ?>">
                                <i class="fa fa-info"></i>
                            </a>
                        </td>
                        <td>
                            <a class="btn btn-info"
                               href="<?php echo BASE_URL ?>/?p=imprimir_cotizacion&id=<?php echo $cotizacion->id ?>">
                                <i class="fa fa-print"></i>
                            </a>
                        </td>
                        <td>
                            <a class="btn btn-warning"
                               href="<?php echo BASE_URL ?>/?p=editar_cotizacion&id=<?php echo $cotizacion->id ?>">
                                <i class="fa fa-edit"></i>
                            </a>
                        </td>
                        <td>
                            <a class="btn btn-danger"
                               href="<?php echo BASE_URL ?>/?p=eliminar_cotizacion&id=<?php echo $cotizacion->id ?>&tokenCSRF=<?php echo $tokenCSRF ?>">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="row">
    <div class="col-sm">
        <!-- Paginación -->
        <nav aria-label="Page navigation" class="d-flex justify-content-end">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
                    <li class="page-item <?php if ($i == $pagina_actual) echo 'active' ?>">
                        <a class="page-link"
                           href="<?php echo BASE_URL ?>/?p=cotizaciones&pagina=<?php echo $i ?>"><?php echo $i ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
</div>

