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
                                                       CLIENTES</h5> 
                            </div>
                            <div class="col-4">
                                <div class="text-center">
                                <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalUsuario" id="botonCrear"><i class="bi bi-plus-circle-fill"></i> Crear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        
                        
                        
                        
                        <table id="tblcentrocostos" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    
                                    <th width="5%">Id</th>
                                    <th>Razon Social</th>
                                    <th>RUC</th>
                                    
                                    <th>Estado</th>
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
  
      include 'views/modules/modals/clientes.php';
  
  ?>
<script src="<?=media()?>/js/sunat.js?v=2"></script>  
<script type="text/javascript">
        $(document).ready(function(){
            $("#botonCrear").click(function()
            {
                $("#formulario")[0].reset();
                $(".modal-title").text("Crear clientes");
                $("#action").val("Crear");
                $("#operacion").val("Crear");
               
            });
            
            //Aquí código inserción
            $(document).on('submit', '#formulario', function(event){
            event.preventDefault();
            var ruc = $('#ruc').val();
            var razon = $('#razon').val();
           
            
            
		    if(ruc != '' && razon != '' )
                {
                    $.ajax({
                    url: base_url + "/assets/ajax/clientes.php?op=guardar",
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
                url:"/assets/ajax/clientes.php?op=buscar",
                method:"POST",
                data:{id_usuario:id_usuario},
                dataType:"json",
                success:function(data) {
    console.log(data);				
    $('#modalUsuario').modal('show');

    // Datos principales
    $('#dni').val(data.ruc);
    $('#razon').val(data.razon);
    $('#usuario_sol').val(data.usuario_sol);
    $('#clave_sol').val(data.clave_sol);

    // Datos SUNAT
    $('#idgre').val(data.idgre);
    $('#secretgre').val(data.secretgre);

    // Orígenes y cuentas
    $('#origendt').val(data.origendt);
    $('#cuentact').val(data.cuentadt); // 👈 el input se llama "cuentact", pero el JSON tiene "cuentadt"
    $('#origencompras').val(data.origencompras);
    $('#cuenta42soles').val(data.cuenta42soles);
    $('#cuenta42dolar').val(data.cuenta42dolar);
    $('#origentventas').val(data.origenteventas);
    $('#cuenta12soles').val(data.cuenta12soles);
    $('#cuenta12dolar').val(data.cuenta12dolar);
    $('#origenhonorarios').val(data.origenhonorarios);
    $('#cuentarhsoles').val(data.cuentarhsoles);
    $('#cuentarhdolar').val(data.cuentarhdolar);
    $('#cuenta40igv').val(data.cuenta40igv);

    // Datos ocultos
    $('#id_usuario').val(id_usuario);
    $('#action').val("Editar");
    $('#operacion').val("Editar");

    $('.modal-title').text("Editar Cliente");
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
                        url:base_url+"/assets/ajax/clientes.php?op=eliminar",
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
                    url: base_url+"/assets/ajax/clientes.php?op=clientes",
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
            
            
            
            



        });         
    </script>
      
      
 
  
  
  
  
  
  
  
  
</body>

</html>