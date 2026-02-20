<!-- Modal Clientes (tbl_empresas) -->
<div class="modal fade" id="modalClientesEmpresas" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Registrar Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formClientesEmpresas" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">RUC</label>
              <input type="text" name="ruc" id="ce_ruc" maxlength="11" class="form-control">
            </div>
            <div class="col-md-8">
              <label class="form-label">Razón Social</label>
              <input type="text" name="razon" id="ce_razon" class="form-control" oninput="this.value=this.value.toUpperCase();">
            </div>
            <div class="col-md-8">
              <label class="form-label">Dirección</label>
              <input type="text" name="direccion" id="ce_direccion" class="form-control" oninput="this.value=this.value.toUpperCase();">
            </div>
            <div class="col-md-4">
              <label class="form-label">Paquetes</label>
              <input type="text" name="paquetes" id="ce_paquetes" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label">Fecha Inicio</label>
              <input type="date" name="fecha_inicio" id="ce_inicio" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label">Fecha Fin</label>
              <input type="date" name="fecha_fin" id="ce_fin" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label">Estado</label>
              <select name="estado" id="ce_estado" class="form-select">
                <option value="1" selected>Activo</option>
                <option value="0">Inactivo</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="id" id="ce_id">
          <input type="hidden" name="operacion" id="ce_operacion">
          <button type="submit" class="btn btn-success" id="ce_action">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
