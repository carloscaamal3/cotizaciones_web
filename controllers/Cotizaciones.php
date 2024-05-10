<?php

class Cotizaciones
{
    public static function nueva($idCliente, $descripcion, $fecha)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("insert into cotizaciones(idUsuario, idCliente, descripcion, fecha) VALUES (?, ?, ?, ?);");
        return $sentencia->execute([SesionService::obtenerIdUsuarioLogueado(), $idCliente, $descripcion, $fecha]);
    }

    public static function eliminarServicio($idServicio)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("delete servicios_cotizaciones
            from servicios_cotizaciones
            inner join cotizaciones on cotizaciones.idUsuario = ?
            and
            servicios_cotizaciones.id = ?");
        return $sentencia->execute([SesionService::obtenerIdUsuarioLogueado(), $idServicio]);
    }

    public static function eliminarCaracteristica($idCaracteristica)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("delete caracteristicas_cotizaciones 
            from caracteristicas_cotizaciones 
            inner join cotizaciones on cotizaciones.idUsuario = ? and caracteristicas_cotizaciones.id = ?;");
        return $sentencia->execute([SesionService::obtenerIdUsuarioLogueado(), $idCaracteristica]);
    }

    public static function obtenerServicioPorId($idServicio)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select servicios_cotizaciones.id, idCotizacion, servicio, costo, tiempoEnMinutos, multiplicador
            from servicios_cotizaciones
            inner join cotizaciones on cotizaciones.idUsuario = ? and servicios_cotizaciones.id = ?");
        $sentencia->execute([SesionService::obtenerIdUsuarioLogueado(), $idServicio]);
        return $sentencia->fetch(PDO::FETCH_OBJ);
    }

    public static function obtenerCaracteristicaPorId($idCaracteristica)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select caracteristicas_cotizaciones.id, idCotizacion, caracteristica
            from caracteristicas_cotizaciones
            inner join cotizaciones on cotizaciones.idUsuario = ? and caracteristicas_cotizaciones.id = ?");
        $sentencia->execute([SesionService::obtenerIdUsuarioLogueado(), $idCaracteristica]);
        return $sentencia->fetch(PDO::FETCH_OBJ);
    }

    public static function serviciosPorId($idCotizacion)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select servicios_cotizaciones.id, servicio, costo, tiempoEnMinutos, multiplicador, iva
            from servicios_cotizaciones
            inner join cotizaciones on cotizaciones.id = servicios_cotizaciones.idCotizacion and cotizaciones.id = ?
            and cotizaciones.idUsuario = ?;");
        $sentencia->execute([$idCotizacion, SesionService::obtenerIdUsuarioLogueado()]);
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }

    public static function caracteristicasPorId($idCotizacion)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select caracteristicas_cotizaciones.id, idCotizacion, caracteristica
            from caracteristicas_cotizaciones
            inner join cotizaciones on cotizaciones.id = caracteristicas_cotizaciones.idCotizacion and cotizaciones.id = ? and cotizaciones.idUsuario = ?;");
        $sentencia->execute([$idCotizacion, SesionService::obtenerIdUsuarioLogueado()]);
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }

    public static function agregarServicio($idCotizacion, $servicio, $costo, $tiempoEnMinutos, $multiplicador, $ivaSelect, $totalConIva)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("insert into servicios_cotizaciones (idCotizacion, servicio, costo, tiempoEnMinutos, multiplicador, iva, total)
        values ((select id from cotizaciones where cotizaciones.idUsuario = ? and cotizaciones.id = ?), ?, ?, ?, ?, ?, ?);");
        return $sentencia->execute([
            SesionService::obtenerIdUsuarioLogueado(),
            $idCotizacion,
            $servicio,
            $costo,
            $tiempoEnMinutos,
            $multiplicador,
            $ivaSelect,
            $totalConIva
        ]);
    }

    public static function agregarCaracteristica($idCotizacion, $caracteristica)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("insert into caracteristicas_cotizaciones
            (idCotizacion, caracteristica)
            values
            ((select id from cotizaciones where cotizaciones.idUsuario = ? and cotizaciones.id = ?), ?);");
        return $sentencia->execute([SesionService::obtenerIdUsuarioLogueado(), $idCotizacion, $caracteristica]);
    }

    public static function actualizarServicio($idServicio, $servicio, $costo, $tiempoEnMinutos, $multiplicador)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("update servicios_cotizaciones
            inner join cotizaciones on servicios_cotizaciones.idCotizacion = cotizaciones.id and cotizaciones.idUsuario = ?
            set servicio        = ?,
            costo           = ?,
            tiempoEnMinutos = ?,
            multiplicador   = ?
            where servicios_cotizaciones.id = ?;");
        return $sentencia->execute([SesionService::obtenerIdUsuarioLogueado(), $servicio, $costo, $tiempoEnMinutos, $multiplicador, $idServicio]);
    }

    public static function actualizarCaracteristica($idCaracteristica, $caracteristica)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("update caracteristicas_cotizaciones
            inner join cotizaciones on caracteristicas_cotizaciones.idCotizacion = cotizaciones.id and cotizaciones.idUsuario = ?
            set
            caracteristica = ?
            where caracteristicas_cotizaciones.id = ?;");
        return $sentencia->execute([SesionService::obtenerIdUsuarioLogueado(), $caracteristica, $idCaracteristica]);
    }

    public static function todas()
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select
            cotizaciones.id, clientes.razonSocial, cotizaciones.descripcion, cotizaciones.fecha, cotizaciones.total
            from clientes inner join cotizaciones
            on cotizaciones.idCliente = clientes.id and cotizaciones.idUsuario = ?;");
        $sentencia->execute([SesionService::obtenerIdUsuarioLogueado()]);
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }

    public static function porId($id)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("select
            cotizaciones.id, clientes.razonSocial, clientes.email, clientes.telefono, cotizaciones.descripcion, cotizaciones.fecha, cotizaciones.idCliente
            from clientes inner join cotizaciones
            on cotizaciones.idCliente = clientes.id and cotizaciones.idUsuario = ?
            where cotizaciones.id = ?;");
        $sentencia->execute([SesionService::obtenerIdUsuarioLogueado(), $id]);
        return $sentencia->fetch(PDO::FETCH_OBJ);
    }

    public static function actualizar($id, $idCliente, $descripcion, $fecha)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("update cotizaciones set
            idCliente = ?,
            descripcion = ?,
            fecha = ?
            where id = ? and idUsuario = ?");
        return $sentencia->execute([$idCliente, $descripcion, $fecha, $id, SesionService::obtenerIdUsuarioLogueado()]);
    }

    public static function eliminar($id)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("delete from cotizaciones where id = ? and idUsuario = ?;");
        return $sentencia->execute([$id, SesionService::obtenerIdUsuarioLogueado()]);
    }
    //obtener por paginacion
    public static function obtenerCotizacionesPaginadas($pagina_actual, $resultados_por_pagina)
    {
        $bd = BD::obtener();
        $desplazamiento = ($pagina_actual - 1) * $resultados_por_pagina;
        $sentencia = $bd->prepare("SELECT
            cotizaciones.id, clientes.razonSocial, cotizaciones.descripcion, cotizaciones.fecha, cotizaciones.total
            FROM clientes INNER JOIN cotizaciones
            ON cotizaciones.idCliente = clientes.id AND cotizaciones.idUsuario = ?
            LIMIT ? OFFSET ?;");
        $sentencia->execute([SesionService::obtenerIdUsuarioLogueado(), $resultados_por_pagina, $desplazamiento]);
        return $sentencia->fetchAll(PDO::FETCH_OBJ);
    }
    

// Función para buscar cotizaciones por término de búsqueda en cualquier campo
    public static function buscarCotizaciones() {
        $termino_busqueda = isset($_GET['q']) ? $_GET['q'] : '';

        if (!empty($termino_busqueda)) {
            $bd = BD::obtener();
            $sentencia = $bd->prepare("SELECT
                cotizaciones.id, clientes.razonSocial, cotizaciones.descripcion, cotizaciones.fecha, cotizaciones.idCliente, cotizaciones.total
                FROM clientes INNER JOIN cotizaciones
                ON cotizaciones.idCliente = clientes.id AND cotizaciones.idUsuario = ?
                WHERE cotizaciones.id LIKE ? OR clientes.razonSocial LIKE ? OR cotizaciones.descripcion LIKE ? OR cotizaciones.fecha LIKE ?");
            $idUsuario = SesionService::obtenerIdUsuarioLogueado();
            $termino = "%$termino_busqueda%";
            $sentencia->execute([$idUsuario, $termino, $termino, $termino, $termino]);
            return $sentencia->fetchAll(PDO::FETCH_OBJ);
        } else {
        // Si no hay término de búsqueda, devuelve un array vacío
            return [];
        }
    }
}
