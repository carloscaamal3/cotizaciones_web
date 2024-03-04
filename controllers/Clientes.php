<?php

class Clientes
{
    public static function nuevo($razonSocial, $direccion, $telefono, $email)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("insert into clientes(razonSocial, direccion, telefono, email,  idUsuario) VALUES (?, ?, ?, ?, ?);");
        return $sentencia->execute([$razonSocial, $direccion, $telefono, $email, SesionService::obtenerIdUsuarioLogueado()]);
    }

    public static function actualizar($id, $razonSocial, $direccion, $telefono, $email)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("update clientes set razonSocial = ?, direccion = ?, telefono = ?, email = ? where id = ? and idUsuario = ?;");
        return $sentencia->execute([$razonSocial, $direccion, $telefono, $email, $id, SesionService::obtenerIdUsuarioLogueado()]);
    }

    public static function todos()
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select id, razonSocial, direccion, telefono, email from clientes where idUsuario = ?;");
        $sentencia->execute([SesionService::obtenerIdUsuarioLogueado()]);
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }

    public static function porId($id)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select id, razonSocial, direccion, telefono, email from clientes where id = ? and idUsuario = ?;");
        $sentencia->execute([$id, SesionService::obtenerIdUsuarioLogueado()]);
        return $sentencia->fetch(PDO::FETCH_OBJ);
    }

    public static function eliminar($id)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("delete from clientes where id = ? and idUsuario = ?;");
        return $sentencia->execute([$id, SesionService::obtenerIdUsuarioLogueado()]);
    }
    //obtener por paginacion
    public static function obtenerClientesPaginadas($pagina_actual, $resultados_por_pagina)
    {
        $bd = BD::obtener();
        $desplazamiento = ($pagina_actual - 1) * $resultados_por_pagina;
        $sentencia = $bd->prepare("SELECT
            clientes.id, clientes.razonSocial, clientes.direccion, clientes.telefono, clientes.email
            FROM clientes where  clientes.idUsuario = ?
            LIMIT ? OFFSET ?;");
        $sentencia->execute([SesionService::obtenerIdUsuarioLogueado(), $resultados_por_pagina, $desplazamiento]);
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }
    public static function buscarClientes() {
    $termino_busqueda = isset($_GET['c']) ? $_GET['c'] : '';

    if (!empty($termino_busqueda)) {
        $bd = BD::obtener();
        $idUsuario = SesionService::obtenerIdUsuarioLogueado();
        $termino = "%$termino_busqueda%";
        $sentencia = $bd->prepare("SELECT
            clientes.id, clientes.razonSocial, clientes.direccion, clientes.telefono, clientes.email
            FROM clientes
            WHERE (clientes.id LIKE ? OR clientes.razonSocial LIKE ? OR clientes.direccion LIKE ? OR clientes.telefono LIKE ? OR clientes.email LIKE ?) AND clientes.idUsuario = ?");
        $sentencia->execute([$termino, $termino, $termino, $termino, $termino, $idUsuario]);
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    } else {
        // Si no hay término de búsqueda, devuelve un array vacío
        return [];
    }
}

}
