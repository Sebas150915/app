<!-- Modal -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark" style="color:white">
        <h5 class="modal-title" id="exampleModalLabel">Crear Concepto de Gasto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
        <form method="POST" id="formulario" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-body">
                    <label for="nombre">Ingrese el nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" oninput="this.value=this.value.toUpperCase();">
                    <br />

                    <label for="apellidos">Ingrese el Cuenta Contable</label>
                    <input type="text" name="codigo" id="codigo" class="form-control" oninput="this.value=this.value.toUpperCase();">
                    <br />

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="id_usuario" id="id_usuario">
                    <input type="hidden" name="operacion" id="operacion">             
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Crear">
                </div>
            </div>
        </form>
      </div>     
  </div>
</div>
