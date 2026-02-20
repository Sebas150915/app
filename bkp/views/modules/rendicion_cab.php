<?php 

$idrendicion = $rutas[1];


?>


<!doctype html>
<html lang="en">

<head>
  <?php include 'views/templates/head.php'?>
   <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
      
      /* 🔹 Compactar y alinear el contenido de la tabla */
#detallecab {
  white-space: nowrap;         /* evita que el texto se rompa en varias líneas */
  width: 100%;
}

#detallecab th,
#detallecab td {
  text-align: center;          /* centra todo el texto */
  vertical-align: middle;      /* centra verticalmente */
  padding: 4px 6px !important; /* reduce espacio interno */
  font-size: 12px;             /* tamaño compacto */
}

/* 🔹 Hace que el contenedor permita scroll horizontal si es necesario */
.dataTables_wrapper {
  overflow-x: auto;
}

.btn-danger {
  --bs-btn-padding-x: 4px;
  --bs-btn-padding-y: 4px;
  --bs-btn-border-radius: 5px;
  }
.derecha
{
    padding: 4px 4px;
    color: black;
    text-align:end;
    border-radius: 3px;
    
    
}

.izquierda
{
    padding: 4px 4px;
    color: black;
    
    border-radius: 3px;
    
    
}

.modal {
 
  --bs-modal-width: 90%;
}

.conceptogasto, .centro-costos, .presupuesto-cb,.condicion
{
    padding:5px;
    font-size: 9px;
    color:#1b3bea;
    font-weight: bold;
}
.form-select-lg
{
    border-radius: 3px;
}
.body-wrapper > .container-fluid, .body-wrapper > .container-sm, .body-wrapper > .container-md, .body-wrapper > .container-lg, .body-wrapper > .container-xl, .body-wrapper > .container-xxl {
  max-width: 95%;
  margin: 0 auto;
  padding: 30px;
    padding-top: 30px;
  -webkit-transition: 0.2s ease-in;
  transition: 0.2s ease-in;
}
.text-right
{
    text-align: right;
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
      <div class="container-fluid" style="width:95%">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border border-dark">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-6 col-12">
                                 <h5 class="card-title d-flex align-items-center gap-2 mb-4">
                                                      COMPROBANTES</h5> 
                            </div>
                             <div class="col-sm-3 col-12">
                                <div class="text-center">
                                <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalUsuario" id="botonCrear"><i class="bi bi-plus-circle-fill"></i> Documentos
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-3 col-12">
                          <div class="text-center">
                            <button type="button" class="btn btn-success w-100 mt-1" 
                                    data-bs-toggle="modal" data-bs-target="#modalOth" id="botonOtros">
                              <i class="bi bi-plus-circle-fill"></i> Doc. Otros
                            </button>
                          </div>
                        </div>
                        </div>
                    </div>
                    <div class="card-body">
                        
                       
                        
                        <input type="hidden" id="idrendicion" name="idrendicion" value="<?=$idrendicion?>"> 
                        <table id="tblcentrocostos" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    
                                    <th width="5%">ID</th>
                                    <th>PROVEEDOR</th>
                                    <th>DESCRIPCION</th>
                                    <th>TIP.</th>
                                    <th>NUMERO</th>
                                    <th width="5%">FECHA</th>
                                    <th width="5%">CC</th>
                                    <th width="5%">PRE</th>
                                    <th width="5%">IMPORTE</th>
                                    
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
  
  <?php
      include 'views/modules/modals/rendiciones_otros.php';
      include 'views/modules/modals/rendiciones_cab.php';
      
  
  ?>
  
    <script src="<?=base_url()?>/assets/js/rendiciones.js?v=2458966"></script>
     <script src="<?=base_url()?>/assets/js/funciones.js?v=10"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      
 
  
  
  
  
  
  
  
  
</body>

</html>