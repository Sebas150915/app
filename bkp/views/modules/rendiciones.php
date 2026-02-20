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
                                                       RENDICIONES</h5> 
                            </div>
                            <div class="col-4">
                                <div class="text-center">
                                <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalUsuario" id="botonCrear"><i class="bi bi-plus-circle-fill"></i> Nueva Rendicion
                                    </button>
                                </div>
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
                        
                        
                        <table id="tblcentrocostos" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    
                                    <th width="7%">ID</th>
                                    <th>DESCRIPCION</th>
                                    <th width="15%">FECHA</th>
                                    <th width="7%">CC</th>
                                    <th width="7%">PRE</th>
                                    <th width="7%">IMPORTE</th>
                                    <th width="7%">ESTADO</th>
                                    <th width="5%">ACCIONES</th>
                                    
                                    
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
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
  
  <?php
  
      include 'views/modules/modals/rendiciones.php';
  
  ?>
  
<script type="text/javascript">
        $(document).ready(function(){
            $("#botonCrear").click(function()
            {
                $("#formulario")[0].reset();
                $(".modal-title").text("Crear Rendicion");
                $("#action").val("Crear");
                $("#operacion").val("Crear");
               
            });
            
            //Aquí código inserción
            $(document).on('submit', '#formulario', function(event){
            event.preventDefault();
            var nombres = $('#nombre').val();
            var fecha   = $('#fecha').val();
            var cc      = $('#cc').val();
            var pre     = $('#pre').val();
            var importe = $('#importe').val();
            let cliente_id = $('#clientes').val();
            
            
            
		    if(nombres != '' && fecha != '' && importe != '' )
                {
                    $.ajax({
                    url: base_url + "/assets/ajax/rendiciones.php?op=guardar&cliente=" + cliente_id,
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    dataType: 'json', // <--- 🔹 Esta línea es clave
                    success: function(data) {
                    console.log(data); // Ver el objeto completo
                    if (data.respuesta) {
                    alert(data.respuesta); // <--- Muestra "Registro creado"
                    } else {
                    alert("Error al procesar la respuesta del servidor");
                    }
                    $('#formulario')[0].reset();
                    $('#modalUsuario').modal('hide');
                    dataTable.ajax.reload();
                    },
                    error: function(xhr, status, error) {
                    console.error("Error en la petición:", error);
                    alert("Error al guardar el registro.");
                    }
                    });

                }
                else
                {
                    alert("Algunos campos son obligatorios");
                }
	        });

            //Funcionalida de editar
            $(document).on('click', '.editar', function(){		
            var id_usuario = $(this).attr("id");		
            $.ajax({
                url:"/assets/ajax/rendiciones.php?op=buscar",
                method:"POST",
                data:{id_usuario:id_usuario},
                dataType:"json",
                success:function(data)
                    {
                        //console.log(data);				
                        $('#modalUsuario').modal('show');
                        $('#nombre').val(data.descripcion);
                        $('#fecha').val(data.fecharendicion);
                        $('#importe').val(data.importe);
                        
                        $('#cc').val(data.cc).trigger('change');
                        $('#pre').val(data.pre).trigger('change');
                        
                        
                       
                        $('.modal-title').text("Editar Rendicion");
                        $('#id_usuario').val(id_usuario);
                        
                        $('#action').val("Editar");
                        $('#operacion').val("Editar");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    }
                })
	        });

            //Funcionalida de borrar
            $(document).on('click', '.borrar', function(){
                var id_usuario = $(this).attr("id");
                
                if(confirm("Esta seguro de borrar este registro : " + id_usuario))
                {
                    $.ajax({
                        url:base_url+"/assets/ajax/rendiciones.php?op=eliminar",
                        method:"POST",
                        data:{id_usuario:id_usuario},
                        success:function(data)
                        {
                            alert(data.respuesta);
                            dataTable.ajax.reload();
                        }
                    });
                }
                else
                {
                    return false;	
                }
            });
            
            //dataTable
            var dataTable = $('#tblcentrocostos').DataTable({
                
                
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url: base_url+"/assets/ajax/rendiciones.php?op=rendiciones",
                    type: "POST"
                },
                "columnsDefs":[
                    {
                    "targets":[0, 3, 4],
                    "orderable":false,
                    },
                ],
                "language": {
                "decimal": "",
                "emptyTable": "No hay registros",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
            });
            
            
            // =============== GENERAR EXCEL ===============//
            
            // === EXPORTAR A EXCEL ===
$(document).on('click', '.detalle2', function(e) {
    e.preventDefault();
    let idRendicion = $(this).attr('href').split('/').pop();
    let idcliente  = $('#clientes').val();

    Swal.fire({
        title: 'Generando Excel...',
        text: 'Por favor espera un momento',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    $.ajax({
        url: base_url + '/assets/ajax/rendiciones.php?op=reporte_excel',
        method: 'POST',
        data: { idrendicion: idRendicion,idcliente:idcliente },
        dataType: 'json',
       success: function(response) {
    Swal.close();

    if (!response || response.length === 0) {
        Swal.fire('Sin datos', 'No se encontraron movimientos en esta rendición', 'warning');
        return;
    }

    const wb = XLSX.utils.book_new();
    const ws_data = [];

    // === CABECERA ===
    ws_data.push(["EMPRESA    :", response.razempresa, "", ""]);
    ws_data.push(["RUC        :", response.rucempresa, "", ""]);
    ws_data.push(["SEDE/LOCAL :", "", "", ""]);
    ws_data.push([]);
    ws_data.push(["RENDICIÓN DEL DÍA " + response.fecha, "", "", ""]);
    ws_data.push([]);

    // === CONCEPTOS ===
    ws_data.push(["CONCEPTO", "", "", "IMPORTE"]);
    let totalConcepto = 0;
    response.conceptos.forEach(row => {
        ws_data.push([row.nombre, "", "", parseFloat(row.total)]);
        totalConcepto += parseFloat(row.total);
    });
    ws_data.push(["TOTAL", "", "", totalConcepto]);
    ws_data.push([]);

    // === DETALLES ===
    ws_data.push(["DESCRIPCIÓN", "NUM DOC", "FECHA", "IMPORTE"]);
    let totalDetalle = 0;
    response.detalles.forEach(row => {
        ws_data.push([row.glosa, row.iddocumento, row.fecha, parseFloat(row.importepago)]);
        totalDetalle += parseFloat(row.importepago);
    });
    ws_data.push(["TOTAL", "", "", totalDetalle]);

    // ✅ Crear hoja recién ahora
    const ws = XLSX.utils.aoa_to_sheet(ws_data);

    // === COMBINAR CELDAS ===
    ws['!merges'] = [
        { s: { r: 4, c: 0 }, e: { r: 4, c: 3 } } // "RENDICIÓN DEL DÍA..."
    ];

    // === AJUSTE DE ANCHO ===
    ws['!cols'] = [
        { wch: 40 }, // Columna A
        { wch: 20 }, // Columna B
        { wch: 15 }, // Columna C
        { wch: 15 }  // Columna D
    ];

    // === ESTILOS ===

    // 🔹 Negrita en cabecera
    ["A1","A2","A3"].forEach(cell => {
        if (ws[cell]) ws[cell].s = { font: { bold: true } };
    });

    // 🔹 Título centrado y grande
    if (ws["A5"]) {
        ws["A5"].s = {
            font: { bold: true, sz: 14 },
            alignment: { horizontal: "center", vertical: "center" }
        };
    }

    // 🔹 Encabezados (conceptos y detalles)
    const headers = ["A7","D7","A11","B11","C11","D11"];
    headers.forEach(cell => {
        if (ws[cell]) ws[cell].s = { font: { bold: true }, alignment: { horizontal: "center" } };
    });

    // 🔹 Totales
    for (let r = 0; r < ws_data.length; r++) {
        const a = ws[`A${r+1}`];
        const d = ws[`D${r+1}`];
        if (a && a.v === "TOTAL") {
            a.s = { font: { bold: true } };
            if (d) d.s = { font: { bold: true }, numFmt: "#,##0.00" };
        }
    }

    // 🔹 Formato numérico para importes
    for (let cell in ws) {
        if (cell[0] === 'D' && ws[cell].t === 'n') {
            ws[cell].z = "#,##0.00";
        }
    }

    // === EXPORTAR ===
    XLSX.utils.book_append_sheet(wb, ws, "Rendición");
    XLSX.writeFile(wb, "RENDICION_DEL_DIA_" + response.fecha + ".xlsx");
},
        error: function(xhr, status, error) {
            Swal.fire('Error', 'No se pudo generar el reporte', 'error');
            console.error(error);
        }
    });
});


            
            
            
                // ======== CARGAR CLIENTES ===========
            $.ajax({
                url: base_url + "/assets/ajax/rendiciones.php?op=clientes",
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
dataTable.ajax.url(base_url + "/assets/ajax/rendiciones.php?op=rendiciones&cliente=" + cliente_id).load();

// ======== CARGAR CENTRO DE COSTOS ===========
            $.ajax({
                url: base_url + "/assets/ajax/rendiciones.php?op=centrocostos&cliente=" + cliente_id,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    let opcionescc ;
                    $.each(data, function(i, item) {
                        // Asegúrate de usar el nombre real de tus columnas
                        opcionescc += `<option value="${item.id}">${item.nombre}</option>`;
                    });
                    $("#cc").html(opcionescc);
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar Centro de Costos:", error);
                }
            });

// ======== CARGAR PRESUPUESTOS ===========
            $.ajax({
                url: base_url + "/assets/ajax/rendiciones.php?op=presupuestos&cliente=" + cliente_id,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    let opcionespre ;
                    $.each(data, function(i, item) {
                        // Asegúrate de usar el nombre real de tus columnas
                        opcionespre += `<option value="${item.id}">${item.nombre}</option>`;
                    });
                    $("#pre").html(opcionespre);
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar Presupuesto:", error);
                }
            });




});


 
            





});         
    </script>
      
      
 
  
  
  
  
  
  
  
  
</body>

</html>