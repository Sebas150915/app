<!-- Modal -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark" style="color:white">
        <h5 class="modal-title" id="exampleModalLabel">Crear Rendicion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
        <form method="POST" id="formulario" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-body">
                    <label for="nombre">Descripcion</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" oninput="this.value=this.value.toUpperCase();">
                    <br />

                    
                  
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="apellidos">Fecha</label>
                            <input type="date" name="fecha" id="fecha" class="form-control" >
                        </div>
                        <div class="col-sm-6">
                            <label for="apellidos">Importe</label>
                            <input type="text" name="importe" id="importe" class="form-control" value="0.00">
                        </div>
                        
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="apellidos">Centro de Costos</label>
                            <select class="form-control" name="cc" id="cc">
                            
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="apellidos">Presupuesto</label>
                            <select class="form-control" name="pre" id="pre">
                            
                            </select>
                        </div>
                        
                        
                        
                    </div>

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="id_usuario" id="id_usuario">
                    <input type="hidden" name="operacion" id="operacion">             
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Crear">
                </div>
            </div>
        </form>
      </div>     
  </div>
</div>
