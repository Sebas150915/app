<!-- Modal -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="exampleModalLabel">Registrar Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <form method="POST" id="formulario" enctype="multipart/form-data">
        <div class="modal-body">
          <!-- Datos principales -->
          <div class="row mb-3">
            <div class="col-sm-3">
              <label for="ruc" class="form-label">RUC</label>
              <div class="input-group">
                <button class="btn btn-outline-secondary" type="button" id="botoncito">
                  <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                 <input type="hidden" name="tipo_doc" id="tipo_doc" value="6">
                <input type="text" name="dni" id="dni" maxlength="11" class="form-control" placeholder="Ingrese RUC">
              </div>
            </div>
            <div class="col-sm-5">
              <label for="razon" class="form-label">Razón Social</label>
              <input type="text" name="razon" id="razon" class="form-control" oninput="this.value=this.value.toUpperCase();" placeholder="Ingrese razón social">
            </div>
            <div class="col-sm-2">
              <label for="usuario_sol" class="form-label">Usuario SOL</label>
              <input type="text" name="usuario_sol" id="usuario_sol" class="form-control" oninput="this.value=this.value.toUpperCase();" placeholder="Usuario SOL">
            </div>
            <div class="col-sm-2">
              <label for="clave_sol" class="form-label">Clave SOL</label>
              <input type="text" name="clave_sol" id="clave_sol" class="form-control" placeholder="Clave SOL">
            </div>
          </div>

          <hr>

          <!-- Datos SUNAT -->
          <div class="row mb-3">
            <div class="col-sm-6">
              <label for="idgre" class="form-label">ID GRE</label>
              <input type="text" name="idgre" id="idgre" class="form-control" oninput="this.value=this.value.toUpperCase();" placeholder="Ingrese ID GRE">
            </div>
            <div class="col-sm-6">
              <label for="secretgre" class="form-label">Secret GRE</label>
              <input type="text" name="secretgre" id="secretgre" class="form-control" placeholder="Ingrese Secret GRE">
            </div>
          </div>

          <hr>

          <!-- Orígenes y cuentas -->
          <div class="row mb-3">
            <div class="col-sm-2">
              <label for="origendt" class="form-label">Origen Detracciones</label>
              <input type="text" name="origendt" id="origendt" maxlength="2" class="form-control" oninput="this.value=this.value.toUpperCase();">
            </div>
            <div class="col-sm-2">
              <label for="cuentact" class="form-label">Cuenta Cte.</label>
              <input type="text" name="cuentact" id="cuentact" maxlength="10" class="form-control" oninput="this.value=this.value.toUpperCase();">
            </div>
            <div class="col-sm-2">
              <label for="origencompras" class="form-label">Origen Compras</label>
              <input type="text" name="origencompras" id="origencompras" maxlength="2" class="form-control">
            </div>
            <div class="col-sm-2">
              <label for="cuenta42soles" class="form-label">Cuenta 42 Soles</label>
              <input type="text" name="cuenta42soles" id="cuenta42soles" maxlength="10" class="form-control">
            </div>
            <div class="col-sm-2">
              <label for="cuenta42dolar" class="form-label">Cuenta 42 Dólar</label>
              <input type="text" name="cuenta42dolar" id="cuenta42dolar" maxlength="10" class="form-control">
            </div>
            <div class="col-sm-2">
              <label for="origenventas" class="form-label">Origen Ventas</label>
              <input type="text" name="origenventas" id="origenventas" maxlength="2" class="form-control">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-sm-2">
              <label for="cuenta12soles" class="form-label">Cuenta 12 Soles</label>
              <input type="text" name="cuenta12soles" id="cuenta12soles" maxlength="10" class="form-control">
            </div>
            <div class="col-sm-2">
              <label for="cuenta12dolar" class="form-label">Cuenta 12 Dólar</label>
              <input type="text" name="cuenta12dolar" id="cuenta12dolar" maxlength="10" class="form-control">
            </div>
            <div class="col-sm-2">
              <label for="origenhonorarios" class="form-label">Origen Honorarios</label>
              <input type="text" name="origenhonorarios" id="origenhonorarios" maxlength="2" class="form-control">
            </div>
            <div class="col-sm-2">
              <label for="cuentarhsoles" class="form-label">Cuenta RH Soles</label>
              <input type="text" name="cuentarhsoles" id="cuentarhsoles" maxlength="10" class="form-control">
            </div>
            <div class="col-sm-2">
              <label for="cuentarhdolar" class="form-label">Cuenta RH Dólar</label>
              <input type="text" name="cuentarhdolar" id="cuentarhdolar" maxlength="10" class="form-control">
            </div>
            <div class="col-sm-2">
              <label for="cuenta40igv" class="form-label">Cuenta 40 IGV</label>
              <input type="text" name="cuenta40igv" id="cuenta40igv" maxlength="10" class="form-control">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <input type="hidden" name="id_usuario" id="id_usuario">
          <input type="hidden" name="operacion" id="operacion">
          <input type="submit" name="action" id="action" class="btn btn-success" value="Guardar">
        </div>
      </form>
    </div>
  </div>
</div>
