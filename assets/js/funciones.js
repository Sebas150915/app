// Presionar ENTER
$('#rucoth').on('keypress', function(e) {
    if (e.which === 13) {
        buscarCliente();
    }
});

// Clic en botón
$('#btnBuscarRuc').on('click', function () {
    buscarCliente();
});

function buscarCliente() {
    let ruc = $('#rucoth').val().trim();

    if (ruc.length < 8) {
        Swal.fire({
            icon: 'warning',
            title: 'Dato inválido',
            text: 'Debe ingresar al menos 8 dígitos (DNI o RUC).'
        });
        return;
    }

    $.ajax({
        url: base_url+'assets/ajax/ajax.php',
        type: 'GET',
        data: {
            op: 'buscarCliente',
            ruc: ruc
        },
        dataType: 'json',
        beforeSend: function () {
            $('#razonoth').val('Buscando...');
        },
        success: function (resp) {
            if (resp.length > 0) {
                $('#razonoth').val(resp[0].razon);
            } else 
            {
                $('#razonoth').val('');
                Swal.fire({
                    icon: 'info',
                    title: 'No encontrado',
                    text: 'No se encontró el RUC o DNI en la base de datos.'
                });
                
                
                
            }
        },
        error: function (err) {
            console.log(err.responseText);
            $('#razonoth').val('');
        }
    });
}






// Cada vez que escriben en Base o IGV, recalcula el total
$('#baseimpoth, #igvoth').on('keyup change', function () {
    calcularTotalOth();
});

function calcularTotalOth() {

    let base  = parseFloat($('#baseimpoth').val()) || 0;
    let igv   = parseFloat($('#igvoth').val()) || 0;

    let total = base + igv;

    $('#totaloth').val(total.toFixed(2));
}
