<?php
//$clientes = Clientes::todos();
$tokenCSRF = Utiles::obtenerTokenCSRF();
$resultados_por_pagina = 5;

// Obtiene la página actual, si no se especifica, se establece en 1 por defecto
$pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$clientes = Clientes::obtenerClientesPaginadas($pagina_actual, $resultados_por_pagina);
$tokenCSRF = Utiles::obtenerTokenCSRF();

// Calcula el número total de registros
$total_cotizaciones = count(Clientes::todos());

// Calcula el número total de páginas
$total_paginas = ceil($total_cotizaciones / $resultados_por_pagina);

// Verifica si se ha enviado un término de búsqueda
$termino_busqueda = isset($_GET['c']) ? $_GET['c'] : '';

// Realiza la búsqueda de registros que coincidan con el término de búsqueda
if (!empty($_GET['c'])) {
    $clientes = Clientes::buscarClientes($termino_busqueda);
} else {
    // Si no hay término de búsqueda, muestra todas las registros
    $clientes = Clientes::obtenerClientesPaginadas($pagina_actual, $resultados_por_pagina);
}
?>

<div class="row">
    <div class="col-sm">
        <h3>Clientes</h3>
        <p>Aquí aparecen los clientes</p>
    </div>
</div>


<div class="row">
    <div class="col-sm">
        <p>
            <a href="<?php echo BASE_URL ?>/?p=nuevo_cliente" class="btn btn-success">
                <i class="fa fa-plus"></i> Nuevo cliente
            </a>
        </p>
    </div>
    <?php include_once BASE_PATH . "/includes/publicidad.php" ?>
</div>

<div class="row">
    <div class="col-sm">
        <div class="table-responsive">
         <form method="GET" action="<?php echo BASE_URL ?>/?p=clientes" class="mb-3">
          <div class="input-group mb-3">
              <input type="text" class="form-control" name="c" placeholder="Buscar..."  value="<?php echo htmlentities($termino_busqueda) ?>">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit" >Buscar</button>
            </div>
        </div>
         <!-- <div class="row">
            <div class="col">
                <input type="text" name="q" class="form-control" placeholder="Buscar...">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
            </div>
        </div>-->
    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Dirección</th>
                <th>Telefono</th>
                <th>Email</th>
                <!--<th>Fecha Registro</th>-->
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente) { ?>
                <tr>
                    <td><?php echo $cliente->id ?></td>
                    <td><?php echo htmlentities($cliente->razonSocial) ?></td>
                    <td><?php echo htmlentities($cliente->direccion) ?></td>
                    <td><?php echo htmlentities($cliente->telefono) ?></td>
                    <td><?php echo htmlentities($cliente->email) ?></td>
                    <!--<td><?php echo htmlentities($cliente->fechaRegistro) ?></td>-->
                    <td>
                        <a class="btn btn-warning"
                        href="<?php echo BASE_URL ?>/?p=editar_cliente&id=<?php echo $cliente->id ?>">
                        <i class="fa fa-edit"></i>
                    </a>
                </td>
                <td>
                    <a class="btn btn-danger"
                    href="<?php echo BASE_URL ?>/?p=eliminar_cliente&id=<?php echo $cliente->id ?>&tokenCSRF=<?php echo $tokenCSRF ?>">
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
                        href="<?php echo BASE_URL ?>/?p=clientes&pagina=<?php echo $i ?>"><?php echo $i ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
</div>