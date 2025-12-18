<!-- Modal -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="modalUsuarioLabel">Crear Bancos</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Form -->
      <form method="POST" id="formulario" enctype="multipart/form-data">

        <div class="modal-body">

          <label for="nombre" class="form-label">Ingrese el nombre</label>
          <input type="text" name="nombre" id="nombre" class="form-control" oninput="this.value=this.value.toUpperCase();">

          <br>

          <label for="codigo" class="form-label">Ingrese el Código</label>
          <input type="text" name="codigo" id="codigo" class="form-control">

          <br>

          <label for="origen" class="form-label">Ingrese el Origen</label>
          <input type="text" name="origen" id="origen" class="form-control">

          <br>

          <label for="moneda" class="form-label">Elegir Moneda</label>
          <select id="moneda" name="moneda" class="form-control">
            <option value="PEN">SOLES</option>
            <option value="USD">DÓLARES</option>
          </select>

        </div>

        <div class="modal-footer">
          <input type="hidden" name="id_usuario" id="id_usuario">
          <input type="hidden" name="operacion" id="operacion">
          <button type="submit" name="action" id="action" class="btn btn-success">Crear</button>
        </div>

      </form>

    </div>
  </div>
</div>

