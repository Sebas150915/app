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
                                                       ESTADOS DE CUENTA</h5> 
                            </div>
                           
                        </div>
                    </div>
                    <div class="card-body">
                        
                        <div class="row">
                            <div class="col-sm-3">
                                <select class="form-control w-100" id="clientes">
                            <option value="">--CLIENTE--</option>
                            
                        </select> 
                                
                            </div>
                            
                            
                            <div class="col-sm-1">
                                <select class="form-control w-100" id="anio">
                            <option value="">--AÑO--</option>
                            <?php for($a=2023;$a<=2030;$a++)
                            { ?>
                            <option value="<?=$a?>"><?=$a?></option>
                            <?php } ?>
                            
                        </select> 
                                
                            </div>
                            
                            <div class="col-sm-1">
                                <select class="form-control w-100" id="mes">
                            <option value="">--MES--</option>
                            <?php for($b=1;$b<=12;$b++)
                            { ?>
                            <option value="<?=sprintf("%02d", $b)?>"><?=sprintf("%02d", $b)?></option>
                            <?php } ?>
                            
                        </select> 
                                
                            </div>
                            
                            
                            <div class="col-sm-2">
                                <select class="form-control w-100" id="bancoproceso">
                            <option value="">--BANCO--</option>
                            
                        </select> 
                                
                            </div>
                            
                            
                             <div class="col-sm-3">
                               <input type="file" id="filebco" class="form-control w-100"> 
                                
                            </div>
                            
                            
                            <div class="col-sm-2">
                               <button id="btnbco" class="btn btn-success btn-block w-100">Procesar</button>
                                
                            </div>
                            
                            
                            
                            
                            
                            
                            
                            
                            
                        </div>  
                        
                        <hr>
                        
                        
                        <table id="tblcentrocostos" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    
                                    <th width="5%">Id</th>
                                    <th>Nombre</th>
                                    <th width="15%">Cuenta</th>
                                    <th width="15%">Origen</th>
                                    <th width="15%">Estado</th>
                                    <th width="10%">Editar</th>
                                    <th width="10%">Borrar</th>
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
  
  <?php
  
      include 'views/modules/modals/bancos.php';
  
  ?>
  
<script type="text/javascript">
        $(document).ready(function(){
            $("#botonCrear").click(function()
            {
                $("#formulario")[0].reset();
                $(".modal-title").text("Crear Bancos");
                $("#action").val("Crear");
                $("#operacion").val("Crear");
               
            });
            
            //Aquí código inserción
            $(document).on('submit', '#formulario', function(event){
            event.preventDefault();
            var nombres = $('#nombre').val();
            var codigo = $('#codigo').val();
             var origen = $('#origen').val();
            let cliente_id = $('#clientes').val();
            
            
		    if(nombres != '' && codigo != '' )
                {
                    $.ajax({
                    url: base_url + "/assets/ajax/bancos.php?op=guardar&cliente=" + cliente_id,
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
                url:"/assets/ajax/bancos.php?op=buscar",
                method:"POST",
                data:{id_usuario:id_usuario},
                dataType:"json",
                success:function(data)
                    {
                        //console.log(data);				
                        $('#modalUsuario').modal('show');
                        $('#nombre').val(data.nombre);
                        $('#codigo').val(data.codigo);
                        $('#origen').val(data.origen);
                        $('.modal-title').text("Editar Bancos");
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
                        url:base_url+"/assets/ajax/bancos.php?op=eliminar",
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
                    url: base_url+"/assets/ajax/bancos.php?op=bancos",
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
    dataTable.ajax.url(base_url + "/assets/ajax/bancos.php?op=bancos&cliente=" + cliente_id).load();
});


        });         
    </script>
      
      
 
  
  
  
  
  
  
  
  
</body>

</html>