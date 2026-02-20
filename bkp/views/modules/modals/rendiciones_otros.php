<!-- Modal -->
<div class="modal fade" id="modalOth" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="exampleModalLabel">Documentos</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div class="container-fluid">
          <!-- Fila 1 -->
          <div class="row g-3">
            <div class="col-12 col-sm-6 col-md-3">
              <label for="docideoth" class="form-label">Tip. Doc. Iden.</label>
              <input type="hidden" name="op" id="op" value="guardaroth" class="form-control">
              <select class="form-select" id="docideoth" name="docideoth" required>
                <option value="0">OTROS</option>
                <option value="6">RUC</option>
              </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <label for="rucoth" class="form-label">RUC / DNI</label>
                    <div class="input-group">
                    <input type="text" name="rucoth" id="rucoth" class="form-control" 
                    placeholder="Ingrese RUC o DNI" maxlength="11">
                    
                    <button class="btn btn-primary" type="button" id="btnBuscarRuc">
                    <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            
            
            
            <div class="col-12 col-md-6">
              <label for="razonoth" class="form-label">Razón Social</label>
              <input type="text" name="razonoth" id="razonoth" required class="form-control">
            </div>
          </div>

          <!-- Fila 2 -->
          <div class="row g-3 mt-2">
            <div class="col-12 col-sm-6 col-md-3">
              <label for="docoth" class="form-label">Tip. Doc.</label>
              <select class="form-select" id="docoth" name="docoth" required>
                <option value="00">OTROS</option>
                <option value="03">BOLETA DE VENTA</option>
                <option value="05">BOLETA DE AVIÓN</option>
                <option value="14">RECIBO DE SERV. PÚBLICO</option>
              </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
              <label for="fechaoth" class="form-label">Fecha</label>
              <input type="date" name="fechaoth" id="fechaoth" required class="form-control">
            </div>
            <div class="col-6 col-sm-6 col-md-3">
              <label for="tdocoth" class="form-label">Serie Doc.</label>
              <input type="text" name="tdocoth" id="tdocoth" required maxlength="4" minlength="4" class="form-control">
            </div>
            <div class="col-6 col-sm-6 col-md-3">
              <label for="ndocoth" class="form-label">Num. Doc.</label>
              <input type="text" name="ndocoth" id="ndocoth" required maxlength="10" minlength="1" class="form-control">
            </div>
          </div>

          <!-- Fila 3 -->
          <div class="row g-3 mt-2">
            <div class="col-6 col-sm-6 col-md-3">
              <label for="tcambiooth" class="form-label">Tipo Cambio</label>
              <input type="text" name="tcambiooth" id="tcambiooth" required class="form-control text-end" value="1.000">
            </div>
            <div class="col-6 col-sm-6 col-md-3">
              <label for="baseimpoth" class="form-label">Base Imp.</label>
              <input type="text" name="baseimpoth" id="baseimpoth" required class="form-control text-end" value="0.00">
            </div>
            <div class="col-6 col-sm-6 col-md-3">
              <label for="igvoth" class="form-label">IGV</label>
              <input type="text" name="igvoth" id="igvoth" required class="form-control text-end" value="0.00">
            </div>
            <div class="col-6 col-sm-6 col-md-3">
              <label for="totaloth" class="form-label">Total</label>
              <input type="text" name="totaloth" id="totaloth" required class="form-control text-end" value="0.00">
            </div>
          </div>

          <!-- Fila 4 -->
          <div class="row g-3 mt-2">
            <div class="col-12 col-sm-6 col-md-3">
              <label for="monedaoth" class="form-label">Moneda</label>
              <select class="form-select" id="monedaoth" name="monedaoth" required>
                <option value="PEN">SOLES</option>
                <option value="USD">DÓLARES</option>
              </select>
            </div>
            <div class="col-12 col-md-9">
              <label for="glosaoth" class="form-label">Descripción</label>
              <input type="text" class="form-control" name="glosaoth" id="glosaoth" maxlength="60" minlength="2" required onkeyup="this.value=this.value.toUpperCase();">
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer justify-content-center">
        <button class="btn btn-success" id="agregaroth" name="agregaroth" onclick="guardaroth()">
          <i class="bi bi-plus-circle-fill"></i> Agregar
        </button>
      </div>
    </div>
  </div>
</div>





    
    
