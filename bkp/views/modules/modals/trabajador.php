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
          <input type="hidden" name="cliente_id" id="cliente_id" value="">
          <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-personal" type="button" role="tab">D. Personal</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-laboral" type="button" role="tab">D. Laboral</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-financiero" type="button" role="tab">D. Financiero</button></li>
          </ul>
          <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-personal" role="tabpanel">
          <div class="row">
            <div class="col-md-4 mb-2">
              <label>DNI</label>
              <div class="input-group">
                <input type="text" class="form-control" name="dni" id="dni" required>
                <button class="btn btn-outline-secondary" type="button" id="btnConsultarDni" title="Consultar DNI">
                  <i class="bi bi-search"></i>
                </button>
              </div>
            </div>
            <div class="col-md-4 mb-2">
              <label>Tipo Documento</label>
              <select class="form-control" name="tipo_doc" id="tipo_doc">
                <option value="DNI">DNI</option>
                <option value="CE">CE</option>
                <option value="RUC">RUC</option>
              </select>
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
              <label>Dirección</label>
              <input type="text" class="form-control" name="direccion" id="direccion">
            </div>
            <div class="col-md-4 mb-2">
              <label>Sexo</label>
              <select class="form-control" name="sexo" id="sexo">
                <option value="">--Seleccione--</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
              </select>
            </div>
            <div class="col-md-4 mb-2">
              <label>Estado Civil</label>
              <select class="form-control" name="estado_civil" id="estado_civil">
                <option value="">--Seleccione--</option>
                <option value="SOLTERO">Soltero</option>
                <option value="CASADO">Casado</option>
                <option value="DIVORCIADO">Divorciado</option>
                <option value="VIUDO">Viudo</option>
              </select>
            </div>
            <div class="col-md-4 mb-2">
              <label>F. Nacimiento</label>
              <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento">
            </div>
            <div class="col-md-4 mb-2">
              <label>Nacionalidad</label>
              <input type="text" class="form-control" name="nacionalidad" id="nacionalidad" value="PERÚ">
            </div>
            <div class="col-md-4 mb-2">
              <label>Lib. Militar</label>
              <input type="text" class="form-control" name="lib_militar" id="lib_militar">
            </div>
            <div class="col-md-4 mb-2">
              <label>N° Hijos</label>
              <input type="number" class="form-control" name="n_hijos" id="n_hijos" value="0" min="0">
            </div>
            <div class="col-md-4 mb-2">
              <label>Grado Inst.</label>
              <select class="form-control" name="grado_inst" id="grado_inst">
                <option value="">--Seleccione--</option>
                <option value="PRIMARIA">PRIMARIA</option>
                <option value="SECUNDARIA">SECUNDARIA</option>
                <option value="SUPERIOR TECNICO">SUPERIOR TECNICO</option>
                <option value="SUPERIOR UNIVERSITARIO">SUPERIOR UNIVERSITARIO</option>
                <option value="POSTGRADO">POSTGRADO</option>
              </select>
            </div>
            <div class="col-md-6 mb-2">
              <label>Ocupación</label>
              <input type="text" class="form-control" name="ocupacion" id="ocupacion">
            </div>
            <div class="col-md-6 mb-2">
              <label>Prof./Título</label>
              <input type="text" class="form-control" name="prof_titulo" id="prof_titulo">
            </div>
            <div class="col-md-4 mb-2">
              <label>Teléfono</label>
              <input type="text" class="form-control" name="telefono" id="telefono">
            </div>
            <div class="col-md-4 mb-2">
              <label>Movil</label>
              <input type="text" class="form-control" name="movil" id="movil">
            </div>
            <div class="col-md-4 mb-2">
              <label>Email</label>
              <input type="email" class="form-control" name="email" id="email">
            </div>
          </div>
          </div>
          <div class="tab-pane fade" id="tab-laboral" role="tabpanel">
          <div class="row">
            <div class="col-md-6 mb-2">
              <label>Cargo</label>
              <input type="text" class="form-control" name="cargo" id="cargo">
            </div>
            <div class="col-md-4 mb-2">
              <label>Estado Laboral</label>
              <select class="form-control" name="estado_laboral" id="estado_laboral">
                <option value="">--Seleccione--</option>
                <option value="ACTIVO">ACTIVO</option>
                <option value="SUBSIDIADO">SUBSIDIADO</option>
                <option value="SUSPENDIDO">SUSPENDIDO</option>
              </select>
            </div>
            <div class="col-md-4 mb-2">
              <label>Tipo</label>
              <select class="form-control" name="tipo_personal" id="tipo_personal">
                <option value="">--Seleccione--</option>
                <option value="EMPLEADO">EMPLEADO</option>
                <option value="OBRERO">OBRERO</option>
                <option value="PRACTICANTE">PRACTICANTE</option>
              </select>
            </div>
            <div class="col-md-4 mb-2">
              <label>Área</label>
              <input type="text" class="form-control" name="area" id="area">
            </div>
            <div class="col-md-4 mb-2">
              <label>Departamento</label>
              <input type="text" class="form-control" name="departamento" id="departamento">
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
              <label>Empresa Prestadora</label>
              <input type="text" class="form-control" name="empresa_prestadora" id="empresa_prestadora">
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
              <label>Fecha Contrato</label>
              <input type="date" class="form-control" name="fecha_contrato" id="fecha_contrato">
            </div>
            <div class="col-md-4 mb-2">
              <label>Fecha Cese</label>
              <input type="date" class="form-control" name="fecha_cese" id="fecha_cese">
            </div>
            <div class="col-md-4 mb-2">
              <label>Turno / Horario</label>
              <select class="form-control" name="turno" id="turno">
                <option value="">--Seleccione--</option>
                <option value="HORARIO NORMAL">HORARIO NORMAL</option>
                <option value="HORARIO NOCTURNO">HORARIO NOCTURNO</option>
                <option value="ROTATIVO">ROTATIVO</option>
              </select>
            </div>
            <div class="col-md-4 mb-2">
              <label>Sueldo Básico</label>
              <input type="number" step="0.01" class="form-control" name="basico" id="basico">
            </div>
            <div class="col-md-4 mb-2">
              <label>Asignación Familiar</label>
              <input type="number" step="0.01" class="form-control" name="asignacion_familiar" id="asignacion_familiar">
            </div>
            <div class="col-md-4 mb-2">
              <label>B.Ext/R.Hon/O.Pago (S/)</label>
              <input type="number" step="0.01" class="form-control" name="bono_extra" id="bono_extra" value="0">
            </div>
            <div class="col-md-4 mb-2">
              <label>Moneda</label>
              <select class="form-control" name="moneda" id="moneda">
                <option value="PEN">S/</option>
                <option value="USD">$</option>
              </select>
            </div>
            <div class="col-md-4 mb-2">
              <label>Tipo Boleta</label>
              <select class="form-control" name="tipo_pago" id="tipo_pago">
                <option value="MENSUAL">Mensual</option>
                <option value="QUINCENAL">Quincenal</option>
                <option value="SEMANAL">Semanal</option>
              </select>
            </div>
          </div>
          </div>
          <div class="tab-pane fade" id="tab-financiero" role="tabpanel">
          <div class="row">
            <div class="col-md-6 mb-2">
              <label>ESSALUD (Régimen)</label>
              <input type="text" class="form-control" name="essalud_regimen" id="essalud_regimen">
            </div>
            <div class="col-md-6 mb-2">
              <label>Fecha Inscripción AFP</label>
              <input type="date" class="form-control" name="afp_fecha_inscripcion" id="afp_fecha_inscripcion">
            </div>
            <div class="col-md-6 mb-2 d-flex align-items-end">
              <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" id="chk_afp_mixta">
                <label class="form-check-label" for="chk_afp_mixta">AFP Mixta</label>
              </div>
            </div>
            <div class="col-md-6 mb-2">
              <label>N° Seg./Autogenerado</label>
              <input type="text" class="form-control" name="seguro_autogenerado" id="seguro_autogenerado">
            </div>
            <div class="col-md-6 mb-2">
              <label>N° RUC EPS</label>
              <input type="text" class="form-control" name="ruc_eps" id="ruc_eps">
            </div>
            <div class="col-md-6 mb-2 d-flex align-items-end">
              <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" name="senati" id="senati" value="1">
                <label class="form-check-label" for="senati">Senati</label>
              </div>
            </div>
            <div class="col-md-6 mb-2 d-flex align-items-end">
              <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" name="afecto_impuesto" id="afecto_impuesto" value="1">
                <label class="form-check-label" for="afecto_impuesto">Afecto a Impuesto 5ta/4ta</label>
              </div>
            </div>
            <div class="col-md-6 mb-2">
              <label>Banco Personal C.C.I.</label>
              <input type="text" class="form-control" name="cci" id="cci">
            </div>
            <div class="col-md-6 mb-2">
              <label>Banco Personal Cta S/</label>
              <input type="text" class="form-control" name="cta_soles" id="cta_soles">
            </div>
            <div class="col-md-6 mb-2">
              <label>Banco Personal Cta US$</label>
              <input type="text" class="form-control" name="cta_dolares" id="cta_dolares">
            </div>
            <div class="col-md-6 mb-2">
              <label>Banco CTS</label>
              <input type="text" class="form-control" name="banco_cts" id="banco_cts">
            </div>
            <div class="col-md-6 mb-2">
              <label>Cuenta CTS</label>
              <input type="text" class="form-control" name="cuenta_cts" id="cuenta_cts">
            </div>
            <div class="col-md-6 mb-2">
              <label>Cuenta CTS US$</label>
              <input type="text" class="form-control" name="cuenta_cts_usd" id="cuenta_cts_usd">
            </div>
          </div>
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
