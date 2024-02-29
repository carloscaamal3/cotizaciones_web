<?php
$tokenCSRF = Utiles::obtenerTokenCSRF();
?>
<div class="row">
    <div class="col-sm">
        <h3>Nuevo cliente</h3>
    </div>
</div>
<div class="row">
    <div class="col-sm">
        <form method="post" action="<?php echo BASE_URL ?>/?p=guardar_cliente">
            <input type="hidden" name="tokenCSRF" value="<?php echo $tokenCSRF ?>">
            <div class="form-group">
                <label for="razonSocial">Nombre o razón social</label>
                <input autofocus name="razonSocial" autocomplete="off" required type="text" class="form-control"
                       id="razonSocial" placeholder="Por ejemplo: Luis Cabrera Benito">
            </div>
             <div class="form-group">
                <label for="direccion">Dirección</label>
                <input autofocus name="direccion" autocomplete="off" required type="text" class="form-control"
                       id="direccion">
            </div>
               <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input autofocus name="telefono" counter =  "10" autocomplete="off" required type="text" class="form-control"
                       id="telefono">
            </div>
               <div class="form-group">
                <label for="email">Email</label>
                <input autofocus name="email" autocomplete="off" required type="text" class="form-control"
                       id="email">
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a class="btn btn-success" href="<?php echo BASE_URL ?>/?p=clientes">&larr; Volver</a>
        </form>
    </div>
</div>

<style>
/*.form-group {
    margin-bottom: 15px;
}

.button-group {
    margin-top: 15px;
}

@media (min-width: 768px) {
    .form-group {
        display: flex;
        flex-direction: row;
        align-items: center;
    }
    
    .form-group label {
        flex: 1;
        margin-right: 10px;
    }
    
    .form-group input {
        flex: 2;
    }
}*/
</style>