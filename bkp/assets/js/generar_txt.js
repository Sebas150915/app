/**
 * Funcionalidad para generar archivo TXT y comprimirlo en ZIP
 * Basado en período de año y mes
 */

function generarTxtComprimido() {
    const anio = $("#anio").val();
    const mes = $("#mes").val();
    
    if (!anio || !mes) {
        alert("Por favor seleccione año y mes");
        return;
    }
    
    // Mostrar indicador de carga
    const btnGenerar = $("#btnGenerarTxt");
    const textoOriginal = btnGenerar.html();
    btnGenerar.html('<i class="bx bx-loader-alt bx-spin"></i> Generando...');
    btnGenerar.prop('disabled', true);
    
    $.ajax({
        url: "assets/ajax/generar_txt_compras.php",
        type: "POST",
        data: {
            anio: anio,
            mes: mes
        },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                
                if (data.status === "success") {
                    // Debug: mostrar información en consola
                    console.log("Respuesta del servidor:", data);
                    console.log("Nombre del archivo:", data.nombre_archivo);
                    
                    // Crear enlace temporal para descarga
                    const link = document.createElement('a');
                    link.href = 'data:application/zip;base64,' + data.zip_base64;
                    link.download = data.nombre_archivo + ".zip";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    // Mostrar mensaje de éxito
                    mostrarNotificacion("Archivo generado y descargado exitosamente", "success");
                } else {
                    mostrarNotificacion(data.mensaje || "Error al generar el archivo", "error");
                }
            } catch (e) {
                console.error("Error parsing response:", e);
                mostrarNotificacion("Error al procesar la respuesta del servidor", "error");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error AJAX:", error);
            mostrarNotificacion("Error de conexión al generar el archivo", "error");
        },
        complete: function() {
            // Restaurar botón
            btnGenerar.html(textoOriginal);
            btnGenerar.prop('disabled', false);
        }
    });
}

function mostrarNotificacion(mensaje, tipo) {
    // Crear notificación personalizada
    const notificacion = $(`
        <div class="alert alert-${tipo === 'success' ? 'success' : 'danger'} alert-dismissible fade show" role="alert">
            <i class="bx ${tipo === 'success' ? 'bx-check-circle' : 'bx-x-circle'}"></i>
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);
    
    // Insertar al inicio del card-body
    $(".card-body").prepend(notificacion);
    
    // Auto-ocultar después de 5 segundos
    setTimeout(function() {
        notificacion.alert('close');
    }, 5000);
}

// Inicializar cuando el documento esté listo
$(document).ready(function() {
    // El botón se inicializa desde el HTML
});
