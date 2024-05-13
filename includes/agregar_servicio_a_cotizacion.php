<?php
if (
    empty($_POST["idCotizacion"])
    ||
    empty($_POST["servicio"])
    ||
    empty($_POST["costo"])
    ||
    empty($_POST["tiempoEnMinutos"])
    ||
    empty($_POST["multiplicador"])
    ||
    empty($_POST["iva"])
    ||
    empty($_POST["total"])
    ||
    empty($_POST["tokenCSRF"])
) {
    exit;
}
Utiles::salirSiTokenCSRFNoCoincide($_POST["tokenCSRF"]);

Cotizaciones::agregarServicio($_POST["idCotizacion"], $_POST["servicio"], $_POST["costo"], $_POST["tiempoEnMinutos"], $_POST["multiplicador"], $_POST["iva"], $_POST["total"]);
Utiles::redireccionar("detalles_caracteristicas_cotizacion&id=" . $_POST["idCotizacion"]);