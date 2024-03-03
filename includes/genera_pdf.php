<?php
require_once(__DIR__ . '/tcpdf.php');



if (empty($_GET["id"])) {
    exit("No se proporcionó un ID");
}

$cotizacion = Cotizaciones::porId($_GET["id"]);
if (!$cotizacion) {
    exit("No existe la cotización");
}
$servicios = Cotizaciones::serviciosPorId($_GET["id"]);
$caracteristicas = Cotizaciones::caracteristicasPorId($_GET["id"]);
$ajustes = Ajustes::obtener();

// Crear instancia de TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Establecer información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Nombre');
$pdf->SetTitle('Cotización para ' . $cotizacion->descripcion);
$pdf->SetSubject('Cotización');
$pdf->SetKeywords('Cotización, PDF, TCPDF');

// Establecer márgenes
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Establecer fuente
$pdf->SetFont('helvetica', '', 12);

// Agregar una página
$pdf->AddPage();

// Agregar contenido
$html = '
<div id="app">
    <div class="row">
        <div class="col-sm">
            <h1>Cotización para ' . htmlentities($cotizacion->descripcion) . '</h1>
            <h4>Cliente: ' . htmlentities($cotizacion->razonSocial) . '</h4>
            <span class="badge badge-pill badge-success">' . htmlentities($cotizacion->fecha) . '</span>';
            if (!empty($ajustes->mensajePresentacion)) {
                $html .= '<p>' . htmlentities($ajustes->mensajePresentacion) . '</p>';
            }
$html .= '</div>
    </div>
    <div class="row">
        <div class="col-sm">
            <br>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Costo estimado</th>
                            <th>Tiempo estimado</th>
                        </tr>
                    </thead>
                    <tbody>';

$costoTotal = 0;
$tiempoTotal = 0;
foreach ($servicios as $servicio) {
    $costoTotal += $servicio->costo;
    $tiempoTotal += $servicio->tiempoEnMinutos * $servicio->multiplicador;
    $html .= '
        <tr>
            <td>' . htmlentities($servicio->servicio) . '</td>
            <td>' . number_format($servicio->costo, 2) . '</td>
            <td>' . number_format($servicio->tiempoEnMinutos * $servicio->multiplicador, 2) . '</td>
        </tr>';
}

$html .= '</tbody>
        <tfoot>
            <tr>
                <td><strong>Total</strong></td>
                <td class="text-nowrap"><strong>' . number_format($costoTotal, 2) . '</strong></td>
                <td class="text-nowrap"><strong>' . number_format($tiempoTotal, 2) . '</strong></td>
            </tr>
        </tfoot>
    </table>
</div>
</div>
</div>
<div class="row">
    <div class="col-sm">
        <h2>Características</h2>
        <ul class="list-group">';
foreach ($caracteristicas as $caracteristica) {
    $html .= '<li class="list-group-item">' . htmlentities($caracteristica->caracteristica) . '</li>';
}
$html .= '</ul>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm">';
if (!empty($ajustes->mensajeAgradecimiento)) {
    $html .= '<p>' . htmlentities($ajustes->mensajeAgradecimiento) . '</p>';
}

if (!empty($ajustes->remitente)) {
    $html .= '<p>Atentamente, <strong>' . htmlentities($ajustes->remitente) . '</strong></p>';
}

if (!empty($ajustes->mensajePie)) {
    $html .= '<p>' . htmlentities($ajustes->mensajePie) . '</p>';
}

$html .= '</div>
</div>
<div class="row d-print-block d-sm-none">
    <hr>
    <div class="col-sm">
        Cotización creada en línea. Crea tus cotizaciones y presupuestos online, totalmente gratis:
        <strong>bit.ly/cotizaciones_online</strong>
    </div>
</div>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('cotizacion.pdf', 'I');
?>
