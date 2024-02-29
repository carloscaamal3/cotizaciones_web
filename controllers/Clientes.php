<?php

class Clientes
{
    public static function nuevo($razonSocial, $direccion, $telefono, $email)
    {
        $bd = BD::obtener();
        $sentencia = $bd->prepare("insert into clientes(razonSocial, direccion, telefono, email,  idUsuario) VALUES (?, ?, ?, ?, ?, ?);");
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
}
