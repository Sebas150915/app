<!doctype html>
<html lang="en">

<head>
  <?php include 'views/templates/head.php'?>
  
  

</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
      <?php include 'views/templates/aside.php'?>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header bg-dark">
        <?php include 'views/templates/nav.php'?>
      </header>
      <!--  Header End -->
      <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border border-dark">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                                 <h5 class="card-title d-flex align-items-center gap-2 mb-4">
                                                       REPORTE</h5> 
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        
                        <div class="row">
                            <div class="col-sm-4">
                                <label>Cliente :</label>
                                <select class="form-control" id="clientes">
                            <option value="">--SELECCIONE CLIENTE--</option>
                            
                        </select> 
                                
                            </div>
                            <div class="col-sm-4">
                                <label>Fecha Desde :</label>
                                <input type="date" value="<?=date('Y-m-d')?>" id="fechai" name="fehai" class="form-control">
                            </div>
                            <div class="col-sm-4">
                                <label>Fecha Hasta :</label>
                                <input type="date" value="<?=date('Y-m-d')?>" id="fechaf" name="fehaf" class="form-control">
                            </div>
                        </div>  
                        
                        <hr>
                        
                        
                        <table id="tblreportes" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    
                                    <th width="5%">Id</th>
                                    <th>RAZON SOCIAL</th>
                                    <th>GLOSA</th>
                                    <th>DOCUMENTO</th>
                                    <th>FECHA DOC</th>
                                    <th>CC</th>
                                    <th>PRE</th>
                                    <th>CONDICION</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                       
                        
                    </div>
                </div>
            </div>
        
       
  
       <?php include 'views/templates/pie.php'?>
      </div>
    </div>
  </div>
  <?php include 'views/templates/footer.php'?>
  
  
  
  <script type="text/javascript">
        $(document).ready(function(){
            //dataTable
  var dataTable = $('#tblreportes').DataTable({
  processing: true,
  serverSide: true,
  order: [],
  ajax: {
    url: base_url + "/assets/ajax/rendiciones.php?op=reporte",
    type: "POST",
    data: function(d) {
      d.cliente = $('#clientes').val();
      d.fechai = $('#fechai').val();
      d.fechaf = $('#fechaf').val();
    }
  },
  // ✅ Centrar y mostrar botones arriba
  dom: '<"text-center mb-3"B>frtip',
  buttons: [
    { extend: 'copy', text: '📋 Copiar', className: 'btn btn-secondary secondary' },
    { extend: 'csv', text: '📄 CSV', className: 'btn btn-info info' },
    { extend: 'excel', text: '🧾 Excel', className: 'btn btn-success success' },
    { extend: 'pdf', text: '📕 PDF', className: 'btn btn-danger danger' },
    { extend: 'print', text: '🖨️ Imprimir', className: 'btn btn-warning warning' }
  ],
  columnDefs: [
    { targets: [0, 3, 4], orderable: false }
  ],
  columns: [
    { data: "id" },
    { data: "razonemisor" },
    { data: "descripcion" },
    { data: "movkey" },
    { data: "fechadocsire" },
    { data: "cc" },
    { data: "pre" },
    { data: "condicion" }
],
  language: {
    decimal: "",
    emptyTable: "No hay registros",
    info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
    infoEmpty: "Mostrando 0 a 0 de 0 Entradas",
    infoFiltered: "(Filtrado de _MAX_ total entradas)",
    lengthMenu: "Mostrar _MENU_ Entradas",
    loadingRecords: "Cargando...",
    processing: "Procesando...",
    search: "Buscar:",
    zeroRecords: "Sin resultados encontrados",
    paginate: {
      first: "Primero",
      last: "Último",
      next: "Siguiente",
      previous: "Anterior"
    }
  }
});




// refrescar al cambiar filtros
$('#clientes, #fechai, #fechaf').on('change', function() {
    dataTable.ajax.reload();
});

            
            
            
            
    // ======== CARGAR CLIENTES ===========
$.ajax({
    url: base_url + "/assets/ajax/bancos.php?op=clientes",
    type: "POST",
    dataType: "json",
    success: function(data) {
        let opciones = '<option value="">--SELECCIONE CLIENTE--</option>';
        $.each(data, function(i, item) {
            // Asegúrate de usar el nombre real de tus columnas
            opciones += `<option value="${item.id}">${item.ruc} - ${item.razon}</option>`;
        });
        $("#clientes").html(opciones);
    },
    error: function(xhr, status, error) {
        console.error("Error al cargar clientes:", error);
    }
});


$('#clientes').on('change', function() {
    let cliente_id = $(this).val();
    dataTable.ajax.url(base_url + "/assets/ajax/rendiciones.php?op=reporte&cliente=" + cliente_id).load();
});


        });         
    </script>
  
 
</body>

</html>