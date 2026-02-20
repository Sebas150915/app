let dataTable;
let dataTable2;
        
$(document).ready(function(){
            $('.js-example-basic-single').select2();
            let idrendicion = $('#idrendicion').val();
            //dataTable
             dataTable = $('#tblcentrocostos').DataTable({
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url: base_url+"/assets/ajax/rendiciones.php?op=rendicion_cab&idrendicion="+idrendicion,
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
            
            dataTable2 = $('#detallecab').DataTable({
                
                'serverMethod': 'post',
    "processing": true,
    "serverSide": true,
    "order": [],
    "scrollX": true, // 👈 Habilita scroll horizontal
                "ajax":{
                    url: base_url+"/assets/ajax/rendiciones.php?op=vwcompras&idrendicion="+idrendicion,
                    type: "post",
                    dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
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



//Funcionalida de borrar
$(document).on('click', '.deletecab', function(){
    var id_usuario = $(this).attr("id");
    var data_documento = $(this).attr("data-documento");
    var movkey   = $(this).attr("data-movkey");
    var importe_pago   = $(this).attr("data-pago");

    Swal.fire({
        title: "¿Estás seguro?",
        text: "Se eliminará el registro con ID: " + data_documento,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: base_url + "/assets/ajax/rendiciones.php?op=eliminardet",
                method: "POST",
                dataType: "json",
                data: { id_usuario: id_usuario ,data_documento:data_documento,movkey:movkey,importe_pago:importe_pago},
                success: function(data) {
                    Swal.fire({
                        title: "Eliminado",
                        text: data.respuesta || "El registro fue eliminado correctamente",
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });
                    dataTable.ajax.reload();
                },
                error: function() {
                    Swal.fire({
                        title: "Error",
                        text: "No se pudo eliminar el registro",
                        icon: "error"
                    });
                }
            });
        }
    });
});

$(document).on('click', '.btn-agregar-detalle', function() {
    const $row = $(this).closest('tr');

    const importe_pago  = parseFloat($row.find('.importe_pago').val());
    const pagado  = parseFloat($row.find('.pagado').val());
    const iddocumento   = $row.find('.iddocumento').val() || '';
    
    const glosacompra   = $row.find('.glosacompra').val() || 'COMPRA';
    
    //alert(iddocumento);
    const idrendicion   = parseFloat($row.find('.idrendicion').val()) || 0;
    const idcliente     = parseFloat($row.find('.idcliente').val()) || 0;
    const totaldocsire     = parseFloat($row.find('.totaldocsire').val()) || 0;
    const centrocostos  = $row.find('.centrocostos').val() || '0';
    const presupuestos  = $row.find('.presupuestos').val() || '0';
    
    const condicion  = $row.find('.condicion').val() || '0';
    const conceptogasto  = $row.find('.conceptogasto').val() || '0';

    // Validar campos básicos
    if(importe_pago === '' || importe_pago === 'NaN'){
        alert("Faltan datos obligatorios .");
        return;
    }
    
    let saldopendiente = totaldocsire - pagado - importe_pago;
    
    console.log('totaldocsire:'+totaldocsire+'|pagado:'+pagado+'|importe_pago:'+importe_pago)
    
    if(saldopendiente <0)
    {
        alert('error el saldo a pagar es menor');
        return;
    }

    $.ajax({
        url: base_url + "/assets/ajax/rendiciones.php",
        method: "GET", // puedes usar "POST" si prefieres
        data: {
            op: "guardadetalle",
            importe_pago: importe_pago,
            iddocumento: iddocumento,
            idrendicion: idrendicion,
            idcliente: idcliente,
            centrocostos: centrocostos,
            presupuestos: presupuestos,
            conceptogasto:conceptogasto,
            condicion:condicion,
            glosacompra:glosacompra
            
        },
        beforeSend: function() {
            // Opcional: mostrar spinner o desactivar botón
            $row.find('.btn-agregar-detalle').prop('disabled', true);
        },
        success: function(response) {
            console.log("Respuesta del servidor:", response);
            dataTable.ajax.reload();
            dataTable2.ajax.reload();

            // Opcional: mostrar mensaje o recargar tabla
            if (response.trim() === "ok") {
                alert("Detalle agregado correctamente.");
                // Si usas DataTable, puedes recargarla:
                $('#detallecab').DataTable().ajax.reload(null, false);
            } else {
                alert("Ocurrió un problema: " + response);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en AJAX:", error);
            alert("Error al guardar el detalle.");
        },
        complete: function() {
            $row.find('.btn-agregar-detalle').prop('disabled', false);
        }
    });
});



function guardaroth() {
    const idrendicion = $('#idrendicion').val();

    // Recolectar los datos del formulario
    const datos = {
        op: 'guardaroth',
        docideoth: $('#docideoth').val(),
        rucoth: $('#rucoth').val(),
        razonoth: $('#razonoth').val(),
        docoth: $('#docoth').val(),
        fechaoth: $('#fechaoth').val(),
        tdocoth: $('#tdocoth').val(),
        ndocoth: $('#ndocoth').val(),
        tcambiooth: $('#tcambiooth').val(),
        baseimpoth: $('#baseimpoth').val(),
        igvoth: $('#igvoth').val(),
        totaloth: $('#totaloth').val(),
        monedaoth: $('#monedaoth').val(),
        glosaoth: $('#glosaoth').val()
    };

    // Validación básica
    if (datos.rucoth.trim() === '' || datos.razonoth.trim() === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Campos obligatorios',
            text: 'Debes completar el RUC y la Razón Social.'
        });
        return;
    }

    // Desactivar botón mientras se guarda
    $('#agregaroth').prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Guardando...');

    $.ajax({
        url: base_url+`/assets/ajax/rendiciones.php?op=guardaroth&idrendicion=${idrendicion}`,
        type: 'POST',
        dataType: 'json',
        data: datos,
        success: function(response) {
            console.log(response);

            if (response.status === 'ok') {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: response.msg,
                    timer: 1500,
                    showConfirmButton: false
                });

                // Cerrar modal
                $('#modalOth').modal('hide');

                // Limpiar campos
                $('#modalOth input, #modalOth select').val('');

                // Recargar tabla si existe
                if ($.fn.DataTable.isDataTable('#tblCompras')) {
                    $('#tblCompras').DataTable().ajax.reload(null, false);
                }

            } else if (response.status === 'exists') {
                Swal.fire({
                    icon: 'info',
                    title: 'Documento existente',
                    text: 'Este documento ya fue registrado.'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.msg || 'No se pudo guardar el registro.'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error AJAX',
                text: 'No se pudo conectar con el servidor.'
            });
            console.error(error);
        },
        complete: function() {
            // Reactivar botón
            $('#agregaroth').prop('disabled', false).html('<i class="bi bi-plus-circle-fill"></i> Agregar');
        }
    });
}
