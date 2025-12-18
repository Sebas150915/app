<div class="modal fade" id="modalAFP" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formAFP">
      <div class="modal-content">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title">AFP</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="idafp" name="idafp" value="0">
          <div class="mb-3"><label>Nombre</label><input type="text" id="nombre" name="nombre" class="form-control" required></div>
          <div class="mb-3"><label>Descripcion</label><textarea id="descripcion" name="descripcion" class="form-control"></textarea></div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" type="submit">Guardar</button>
          <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cerrar</button>
        </div>
      </div>
    </form>
  </div>
</div>
