$(document).ready(function(){

    let anioActual = new Date().getFullYear();
    let mesActual = ("0" + (new Date().getMonth() + 1)).slice(-2);

    let dataTable = $('#tblPlanilla').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax:{
            url: base_url + "/assets/ajax/planilla.php?op=listar",
            type: "POST",
            data: { anio: anioActual, mes: mesActual }
        },
        columns: [
            { data: "idpersonal", title: "ID" },
            { data: "nombres", title: "Trabajador" },
            { data: "anio", title: "Año" },
            { data: "mes", title: "Mes" },
            { data: "total_bruto", title: "Bruto" },
            { data: "total_descuento", title: "Descuento" },
            { data: "total_neto", title: "Neto" }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });

    // Botón calcular planilla
    $("#btnCalcularPlanilla").click(function(){
        Swal.fire({
            title: "¿Desea calcular la planilla?",
            text: "Se generará la planilla del mes actual.",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Sí, calcular"
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: base_url + "/assets/ajax/planilla.php?op=calcular",
                    type: "POST",
                    dataType: "json",
                    success: function(data){
                        Swal.fire("Éxito", data.respuesta, "success");
                        dataTable.ajax.reload();
                    },
                    error: function(){
                        Swal.fire("Error", "No se pudo procesar la planilla", "error");
                    }
                });
            }
        });
    });
});
