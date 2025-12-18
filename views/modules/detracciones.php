<!doctype html>
<html lang="es">
<head>
  <?php include 'views/templates/head.php'?>
  <style>
    /* === Estilos personalizados profesionales === */
    body {
      background-color: #f8f9fa;
      font-family: 'Poppins', sans-serif;
    }
    h3.page-title {
      font-weight: 600;
      color: #212529;
      letter-spacing: 0.5px;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      border: none;
    }
    .card-title {
      font-weight: 600;
      color: #343a40 !important;
    }
    label {
      font-weight: 500;
      color: #495057;
    }
    .btn-custom {
      border-radius: 8px;
      font-weight: 500;
      padding: 6px 14px;
    }
    .btn-primary {
      background-color: #0d6efd;
      border: none;
    }
    .btn-primary:hover {
      background-color: #0b5ed7;
    }
    .btn-success {
      background-color: #198754;
      border: none;
    }
    .btn-danger {
      background-color: #dc3545;
      border: none;
    }
    hr {
      margin: 1rem 0;
      border-color: #dee2e6;
    }
    .dataTables_wrapper .dt-buttons {
      float: right;
      margin-bottom: 10px;
    }
    table.dataTable thead th {
      background-color: #343a40;
      color: white;
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6">
    <?php include 'views/templates/aside.php'?>
    <div class="body-wrapper">
      <header class="app-header bg-dark">
        <?php include 'views/templates/nav.php'?>
      </header>

      <div class="container-fluid mt-4">

        <h3 class="text-center page-title mb-4">📑 Gestión de Detracciones</h3>

        <!-- Card Cliente y Carga -->
        <div class="card mb-4">
          <div class="card-body">
            <form id="formUpload" enctype="multipart/form-data">
            <div class="row mb-3">
                <h5 class="card-title">📂 Cargar Archivo TXT</h5>
                
              <div class="col-sm-5">
                <label for="clientes">Cliente</label>
                <select class="form-control" id="filtro_ruc_emisor">
                  <option value="">-- SELECCIONE CLIENTE --</option>
                </select>
              </div>
              <div class="col-sm-5">
                <label for="clientes">Cargar TXT</label>
                <input type="file" name="file" id="file" class="form-control" required>
                  
              </div>
              <div class="col-sm-2">
                <button type="submit" class="btn btn-primary btn-custom mt-4">
                <i class="fas fa-upload"></i> Subir Archivo
              </button>
              </div>
                  
              
            </div>
            </form>
          
          </div>
        </div>

        <!-- Card filtros -->
        <div class="card mb-4">
          <div class="card-body">
            <h5 class="card-title">🔍 Filtros</h5>
            <div class="row">
              <div class="col-md-3">
                <label>Fecha Pago - Desde</label>
                <input type="date" id="filtro_fecha_desde" class="form-control">
              </div>
              <div class="col-md-3">
                <label>Fecha Pago - Hasta</label>
                <input type="date" id="filtro_fecha_hasta" class="form-control">
              </div>
             
              <div class="col-md-3">
                <label>RUC Receptor</label>
                <input type="text" id="filtro_ruc_receptor" class="form-control" placeholder="Ingrese RUC Receptor">
              </div>
            </div>
            <div class="mt-4 text-end">
              <button class="btn btn-success btn-custom" id="btnBuscar">
                <i class="fas fa-search"></i> Aplicar Filtros
              </button>
              <button class="btn btn-danger btn-custom" id="btnEliminar">
                <i class="fas fa-trash-alt"></i> Eliminar por rango
              </button>
            </div>
          </div>
        </div>

        <!-- Card tabla -->
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">📊 Registros</h5>
            <div id="tablaRegistros"></div>
          </div>
        </div>

        <?php include 'views/templates/pie.php'?>
      </div>
    </div>
  </div>

  <?php include 'views/templates/footer.php'?>

  <script>
  $(document).ready(function(){

    let idcliente = $('#filtro_ruc_emisor').val();

    // ======== CARGAR CLIENTES ===========
    $.ajax({
      url: base_url + "/assets/ajax/bancos.php?op=clientes",
      type: "POST",
      dataType: "json",
      success: function(data) {
        let opciones = '<option value="">-- SELECCIONE CLIENTE --</option>';
        $.each(data, function(i, item) {
          opciones += `<option value="${item.id}">${item.ruc} - ${item.razon}</option>`;
        });
        $("#filtro_ruc_emisor").html(opciones);
      },
      error: function(xhr, status, error) {
        console.error("Error al cargar clientes:", error);
      }
    });

    // ======== SUBIR ARCHIVO ===========
$("#formUpload").on("submit", function(e) {
  e.preventDefault(); // Detiene el envío por defecto del formulario

  let idcliente = $('#filtro_ruc_emisor').val(); // Obtener cliente seleccionado

  if (idcliente === '' || idcliente === null) {
    Swal.fire("Atención", "Debe seleccionar un cliente antes de continuar", "warning");
    return; // Detiene la ejecución si no hay cliente
  }

  // Validar si se seleccionó un archivo
  if ($("#file").get(0).files.length === 0) {
    Swal.fire("Atención", "Debe seleccionar un archivo para subir", "warning");
    return;
  }

  let formData = new FormData(this);

  Swal.fire({
    title: "Procesando...",
    text: "Cargando datos en la base de datos",
    allowOutsideClick: false,
    didOpen: () => { Swal.showLoading(); }
  });

  $.ajax({
    url: base_url + "/assets/ajax/detracciones/upload.php?idcliente="+idcliente,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function(resp) {
      Swal.close();
      Swal.fire("Éxito", resp, "success");
      cargarTabla(); // recargar tabla después de subir
    },
    error: function() {
      Swal.close();
      Swal.fire("Error", "Hubo un problema al cargar el archivo", "error");
    }
  });
});


    // ======== CARGAR TABLA ===========
    function cargarTabla(){
        
        if(idcliente = '')
        {
            alert('Debe seleccionar un cliente');
        }
      $.ajax({
        url: base_url+"/assets/ajax/detracciones/listar.php",
        type: "GET",
        data: {
          fecha_desde: $("#filtro_fecha_desde").val(),
          fecha_hasta: $("#filtro_fecha_hasta").val(),
          ruc_emisor: $("#filtro_ruc_emisor").val(),
          ruc_receptor: $("#filtro_ruc_receptor").val()
        },
        success: function(data){
          $("#tablaRegistros").html(data);

          $("#tablaDetracciones").DataTable({
            destroy: true,
            dom: 'Bfrtip',
            buttons: [
              { extend: 'excelHtml5', text: '📥 Exportar a Excel', className: 'btn btn-success btn-sm' }
            ],
            language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" }
          });

          // Editar fecha con SweetAlert2
          $(".btnEditar").click(function(){
            let id = $(this).data("id");
            let fechaActual = $(this).data("fecha");

            Swal.fire({
              title: "Editar Fecha de Pago",
              input: "date",
              inputValue: fechaActual,
              showCancelButton: true,
              confirmButtonText: "Guardar",
              cancelButtonText: "Cancelar"
            }).then((result) => {
              if (result.isConfirmed) {
                let nuevaFecha = result.value;
                if (!nuevaFecha) {
                  Swal.fire("Atención", "Debes seleccionar una fecha", "warning");
                  return;
                }
                $.post(base_url+"/assets/ajax/detracciones/listar.php", 
                  { accion: "editar_fecha", id: id, nueva_fecha: nuevaFecha }, 
                  function(resp){
                    let r = JSON.parse(resp);
                    if(r.success){
                      Swal.fire("Éxito", r.message, "success");
                      cargarTabla();
                    } else {
                      Swal.fire("Error", r.message, "error");
                    }
                  }
                );
              }
            });
          });
        }
      });
    }

    // ======== ACCIONES DE BOTONES ===========
    $("#btnBuscar").click(function()
    { cargarTabla(); });

    $("#btnEliminar").click(function(){
      let desde = $("#filtro_fecha_desde").val();
      let hasta = $("#filtro_fecha_hasta").val();
      if (!desde || !hasta) {
        Swal.fire("Atención", "Debes seleccionar el rango de fechas", "warning");
        return;
      }
      Swal.fire({
        title: "¿Eliminar registros?",
        text: `Se eliminarán todos los registros entre ${desde} y ${hasta}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: base_url+"/assets/ajax/detracciones/eliminar.php",
            type: "POST",
            data: { desde, hasta },
            success: function(resp){
              Swal.fire("Éxito", resp, "success");
              cargarTabla();
            },
            error: function(){
              Swal.fire("Error", "No se pudieron eliminar los registros", "error");
            }
          });
        }
      });
    });

    // ======== CARGA INICIAL ===========
   // cargarTabla();
  });
  </script>
</body>
</html>
