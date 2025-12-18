<!doctype html>
<html lang="en">

<head>
  <?php include 'views/templates/head.php'?>
  
  
  <style>
      .body-wrapper > .container-fluid
      {
          max-width: 95%;
      }
      
      
  </style>
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
                                                       SIRE COMPRAS</h5> 
                            </div>
                            
                        </div>
                    </div>
   <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <select class="form-control" id="clientes">
                    <option value="">--SELECCIONE CLIENTE--</option>
                    
                    </select> 
                
                </div>
            </div>  
                        
                        <hr>
                                  <div class="row">
                                  	<div class="col-sm-12">
    <form id="formUpload" enctype="multipart/form-data" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="file" name="archivo" accept=".txt" required class="form-control">
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn btn-primary">
                <i class="bx bx-upload"></i> Subir y Guardar
            </button>
        </div>

        <div class="col-md-2">
            <select name="anio" id="anio" class="form-control">
                <?php for($y=2024;$y<=2032;$y++): ?>
                    <option value="<?= $y ?>"><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="col-md-2">
            <select name="mes" id="mes" class="form-control">
                <option value="01">Enero</option>
                <option value="02">Febrero</option>
                <option value="03">Marzo</option>
                <option value="04">Abril</option>
                <option value="05">Mayo</option>
                <option value="06">Junio</option>
                <option value="07">Julio</option>
                <option value="08">Agosto</option>
                <option value="09">Setiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
            </select>
        </div>

        <div class="col-md-auto">
            <button type="button" id="btnListar" class="btn btn-success">
                <i class="bx bx-list-ul"></i> Traer Sire
            </button>
        </div>
        
        <div class="col-md-auto">
            <button type="button" id="btnGenerarTxt" class="btn btn-info" onclick="generarTxtComprimido()">
                <i class="bx bx-download"></i> Generar TXT
            </button>
        </div>
    </form>
</div>
                                  </div>
                                  <hr>
                                    <div id="tablaDatos"></div>
                              </div>
                          </div>
                </div>
            </div>
        
       
  
       <?php include 'views/templates/pie.php'?>
      </div>
    </div>
  </div>
  <?php include 'views/templates/footer.php'?>
  
  <?php
  
      include 'views/modules/modals/bancos.php';
  
  ?>
  

      
      
 
  
  
  
  
  
  
  
  
 <!-- Script para generar TXT comprimido -->
<script src="assets/js/generar_txt.js"></script>

<script>
function cargarTabla() {
    var anio = $("#anio").val();
    var mes  = $("#mes").val();
    let cliente_id = $('#clientes').val();

    $.ajax({
        url: "assets/ajax/tabla_compras.php",
        type: "POST",
        data: {anio: anio, mes: mes, cliente_id:cliente_id},
        success: function(data){
            $("#tablaDatos").html(data);

            // Inicializar DataTable SOLO si existe la tabla
            if($("#tablacompras").length){
                $('#tablacompras').DataTable({
                    destroy: true,
                    scrollX: true,         // 👈 scroll horizontal
                    lengthChange: false,
                    buttons: [ 'copy', 'excel', 'pdf', 'print'],
                   columnDefs: [
        { targets: "_all", className: "dt-nowrap" } 
    ]
                }).buttons().container().appendTo('#tablacompras_wrapper .col-md-6:eq(0)');
            }
        }
    });
}

$("#formUpload").on("submit", function(e){
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: base_url+"/assets/ajax/upload_compras.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){
            var res = JSON.parse(response);
            if(res.status === "ok"){
                cargarTabla();
                alert(res.mensaje + " - Insertados: " + res.si_insertados + " | Duplicados: " + res.no_insertados);
            } else {
                alert("Error al procesar archivo");
            }
        }
    });
});

$(document).ready(function(){
    cargarTabla();

    $("#anio, #mes").on("change", function(){
        cargarTabla();
    });



    $("#btnListar").on("click", function()
    {
		var anio = $("#anio").val();
		var mes  = $("#mes").val();
		let cliente_id = $('#clientes').val();
    	$.ajax({
        url: "assets/ajax/compras.php",
        type: "POST",
        data: {anio: anio, mes: mes,cliente_id:cliente_id},
        success: function(data)
        {
            cargarTabla();
        }
    });





        
    });
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





function descargar(tipo, ruc, tipodoc, seriedoc, numerodoc) {
    const data = {
        tipo,
        ruc,
        tipodoc,
        seriedoc,
        numerodoc
    };

    fetch('/assets/ajax/proxy_descarga.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => {
        if (!res.ok) 
            throw new Error("Error en la descarga: " + res.status);
        return res.blob();
    })
    .then(blob => {
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');

        a.href = url;
        a.download = `${ruc}-${tipodoc}-${seriedoc}-${numerodoc}.zip`;
        a.click();

        URL.revokeObjectURL(url);
    })
    .catch(err => {
        console.error(err);
        Swal.fire("Error", "No se pudo descargar el archivo", "error");
    });
}



</script> 
</body>

</html>