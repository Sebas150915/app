<div class="modal fade" id="modalTrabajador" tabindex="-1" aria-labelledby="modalTrabajadorLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="formTrabajador" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" id="modalTrabajadorLabel">Nuevo Trabajador</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="idpersonal" id="idpersonal" value="0">
          <div class="row">
            <div class="col-md-4 mb-2">
              <label>DNI</label>
              <input type="text" class="form-control" name="dni" id="dni" required>
            </div>
            <div class="col-md-4 mb-2">
              <label>Apellido Paterno</label>
              <input type="text" class="form-control" name="apellido_paterno" id="apellido_paterno">
            </div>
            <div class="col-md-4 mb-2">
              <label>Apellido Materno</label>
              <input type="text" class="form-control" name="apellido_materno" id="apellido_materno">
            </div>
            <div class="col-md-6 mb-2">
              <label>Nombres</label>
              <input type="text" class="form-control" name="nombres" id="nombres" required>
            </div>
            <div class="col-md-6 mb-2">
              <label>Cargo</label>
              <input type="text" class="form-control" name="cargo" id="cargo">
            </div>
            <div class="col-md-4 mb-2">
              <label>Banco</label>
              <select class="form-control" name="idbanco" id="idbanco"></select>
            </div>
            <div class="col-md-4 mb-2">
              <label>Cuenta Bancaria</label>
              <input type="text" class="form-control" name="cuenta_bancaria" id="cuenta_bancaria">
            </div>
            <div class="col-md-4 mb-2">
              <label>AFP</label>
              <select class="form-control" name="idafp" id="idafp"></select>
            </div>
            <div class="col-md-4 mb-2">
              <label>Tipo Comisión</label>
              <select class="form-control" name="tipo_comision" id="tipo_comision">
                <option value="Mixta">Mixta</option>
                <option value="Flujo">Flujo</option>
              </select>
            </div>
            <div class="col-md-4 mb-2">
              <label>CUSPP</label>
              <input type="text" class="form-control" name="cuspp" id="cuspp">
            </div>
            <div class="col-md-4 mb-2">
              <label>Centro de Costo</label>
              <select class="form-control" name="idcentro" id="idcentro"></select>
            </div>
            <div class="col-md-4 mb-2">
              <label>Categoría</label>
              <select class="form-control" name="idcategoria" id="idcategoria"></select>
            </div>
            <div class="col-md-4 mb-2">
              <label>Tipo de Contrato</label>
              <select class="form-control" name="idcontrato_tipo" id="idcontrato_tipo"></select>
            </div>
            <div class="col-md-4 mb-2">
              <label>Fecha Ingreso</label>
              <input type="date" class="form-control" name="fecha_ingreso" id="fecha_ingreso">
            </div>
            <div class="col-md-4 mb-2">
              <label>Fecha Cese</label>
              <input type="date" class="form-control" name="fecha_cese" id="fecha_cese">
            </div>
            <div class="col-md-4 mb-2">
              <label>Sueldo Básico</label>
              <input type="number" step="0.01" class="form-control" name="basico" id="basico">
            </div>
            <div class="col-md-4 mb-2">
              <label>Asignación Familiar</label>
              <input type="number" step="0.01" class="form-control" name="asignacion_familiar" id="asignacion_familiar">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </form>
  </div>
</div>
