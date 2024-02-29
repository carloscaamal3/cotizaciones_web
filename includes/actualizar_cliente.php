<?php
if (
    empty($_POST["razonSocial"])
    ||
    empty($_POST["id"])
    ||
    empty($_POST["direccion"])  
    ||
    empty($_POST["telefono"])  
    ||
    empty($_POST["email"]) 
    || 
    empty($_POST["tokenCSRF"])
) {
    exit;
}
Utiles::salirSiTokenCSRFNoCoincide($_POST["tokenCSRF"]);
Clientes::actualizar($_POST["id"], $_POST["razonSocial"], $_POST["direccion"], $_POST["telefono"], $_POST["email"]);
Utiles::redireccionar("clientes");
