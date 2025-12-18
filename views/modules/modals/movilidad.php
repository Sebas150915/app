<div class="modal fade" id="modalMovilidad" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Registrar Planilla de Movilidad</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form id="formMovilidad" method="POST">

        <div class="modal-body">

          <div class="row g-3">

            <div class="col-md-6">
              <label>Empleado</label>
              <select id="id_empleado" name="id_empleado" class="form-select" required>
                <option value="">-- Seleccione --</option>
              </select>
            </div>

            <div class="col-md-3">
              <label>Periodo</label>
              <input type="month" id="periodo" name="periodo" class="form-control" required>
            </div>

            <div class="col-md-3">
              <label>Fecha Emisión</label>
              <input type="date" name="fecha_emision" class="form-control" required value="<?=date('Y-m-d')?>">
            </div>

          </div>

          <hr>

          <button type="button" class="btn btn-success mb-2" id="btnAgregarFila">
            <i class="bi bi-plus-circle"></i> Agregar Movimiento
          </button>

          <table class="table table-bordered" id="tablaMov">
            <thead class="table-dark">
              <tr>
                <th>Día</th>
                <th>Mes</th>
                <th>Año</th>
                <th>Motivo</th>
                <th>Destino</th>
                <th>Importe</th>
                <th></th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

          <h4 class="text-end mt-3">
            TOTAL: <span id="totalMostrar">0.00</span>
          </h4>

        </div>

        <div class="modal-footer">
          <input type="hidden" name="id" id="id_mov">
          <input type="hidden" name="operacion" id="operacion">
          <input type="submit" id="action" value="Crear" class="btn btn-primary">
        </div>

      </form>

    </div>
  </div>
</div>

<script>
// === Agregar fila ===
$("#btnAgregarFila").click(function () {
    let fila = `
    <tr>
      <td><input type="number" class="form-control" name="dia[]" required></td>
      <td><input type="number" class="form-control" name="mes[]" required></td>
      <td><input type="number" class="form-control" name="anio[]" required></td>
      <td><input type="text" class="form-control" name="motivo[]" required></td>
      <td><input type="text" class="form-control" name="destino[]" required></td>
      <td><input type="number" step="0.01" class="form-control importe" name="importe[]" required></td>
      <td><button type="button" class="btn btn-danger btn-sm borrarFila"><i class="bi bi-trash"></i></button></td>
    </tr>`;

    $("#tablaMov tbody").append(fila);
});

// === Eliminar fila ===
$(document).on("click", ".borrarFila", function () {
    $(this).closest("tr").remove();
    calcularTotal();
});

// === Calcular total ===
$(document).on("keyup change", ".importe", function () {
    calcularTotal();
});

function calcularTotal() {
    let total = 0;
    $(".importe").each(function(){
        total += parseFloat($(this).val()) || 0;
    });

    $("#totalMostrar").text(total.toFixed(2));
}
</script>
