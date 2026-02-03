$(document).ready(function () {
  let tabla;

  function initTabla(clienteId) {
    if ($.fn.DataTable.isDataTable('#tblTrabajadores')) {
      $('#tblTrabajadores').DataTable().destroy();
      $('#tblTrabajadores').empty(); // clean headers/body to avoid duplication
      $('#tblTrabajadores').html('<thead><tr><th>ID</th><th>DNI</th><th>Apellidos y Nombres</th><th>Cargo</th><th>Banco</th><th>AFP</th><th>Centro</th><th>Contrato</th><th>Estado</th><th>Editar</th><th>Borrar</th></tr></thead><tbody></tbody>');
    }
    tabla = $('#tblTrabajadores').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: base_url + "assets/ajax/trabajadores.php?op=listar",
        type: "POST",
        data: function (d) {
          d.cliente_id = $('#clientes').val() || clienteId || '';
        }
      },
      columns: [
      { data: 0 },
      { data: 1 },
      { data: 2 },
      { data: 3 },
      { data: 4 },
      { data: 5 },
      { data: 6 },
      { data: 7 },
      { data: 8 },
      { data: 9 },
      { data: 10 }
      ],
      language: {
      "processing": "Procesando...",
      "lengthMenu": "Mostrar _MENU_ registros",
      "zeroRecords": "No se encontraron resultados",
      "emptyTable": "Ningún dato disponible en esta tabla",
      "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "infoFiltered": "(filtrado de un total de _MAX_ registros)",
      "search": "Buscar:",
      "infoThousands": ",",
      "loadingRecords": "Cargando...",
      "paginate": {
        "first": "Primero",
        "last": "Último",
        "next": "Siguiente",
        "previous": "Anterior"
      },
      "aria": {
        "sortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sortDescending": ": Activar para ordenar la columna de manera descendente"
      }
      }
    });
  }

  // cargar selects al abrir modal
  $('#botonCrear').click(function () {
    $('#formTrabajador')[0].reset();
    $('#idpersonal').val(0);
    $('.modal-title').text('Nuevo Trabajador');
    $('#cliente_id').val($('#clientes').val() || '');
    cargarSelects();
  });

  function cargarSelects() {
    const clienteId = $('#clientes').val() || '';
    $.post(base_url + 'assets/ajax/trabajadores.php?op=selects', { cliente_id: clienteId }, function (resp) {
      // bancos
      let opts = '<option value="">--Seleccione--</option>';
      $.each(resp.bancos, function (i, b) { opts += '<option value="' + b.idbanco + '">' + b.nombre + '</option>'; });
      $('#idbanco').html(opts);
      // afps
      opts = '<option value="">--Seleccione--</option>';
      $.each(resp.afps, function (i, a) { opts += '<option value="' + a.idafp + '">' + a.nombre + '</option>'; });
      $('#idafp').html(opts);
      // centros
      opts = '<option value="">--Seleccione--</option>';
      $.each(resp.centros, function (i, c) { opts += '<option value="' + c.idcentro + '">' + c.descripcion + '</option>'; });
      $('#idcentro').html(opts);
      // categorias
      opts = '<option value="">--Seleccione--</option>';
      $.each(resp.categorias, function (i, c) { opts += '<option value="' + c.idcategoria + '">' + c.nombre + '</option>'; });
      $('#idcategoria').html(opts);
      // contratos
      opts = '<option value="">--Seleccione--</option>';
      $.each(resp.contratos, function (i, ct) { opts += '<option value="' + ct.idcontrato_tipo + '">' + ct.nombre + '</option>'; });
      $('#idcontrato_tipo').html(opts);
    }, 'json').fail(function () { Swal.fire('Error', 'No se pudieron cargar las listas', 'error'); });
  }

  // enviar formulario para crear/editar
  $('#formTrabajador').submit(function (e) {
    e.preventDefault();
    let form = new FormData(this);
    form.append('cliente_id', $('#clientes').val() || '');
    $.ajax({
      url: base_url + 'assets/ajax/trabajadores.php?op=guardar',
      method: 'POST',
      data: form,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function (res) {
        Swal.fire('OK', res.respuesta, 'success');
        $('#modalTrabajador').modal('hide');
        tabla.ajax.reload();
      }
    });
  });

  // consulta DNI
  $(document).on('click', '#btnConsultarDni', function () {
    const tipo = $('#tipo_doc').val();
    const dni = ($('#dni').val() || '').trim();
    if (tipo !== 'DNI') { Swal.fire('Aviso', 'El tipo de documento debe ser DNI', 'info'); return; }
    if (!/^\d{8}$/.test(dni)) { Swal.fire('Aviso', 'Ingrese un DNI de 8 dígitos', 'warning'); return; }
    $('#btnConsultarDni').prop('disabled', true);
    $.getJSON(base_url + 'assets/ajax/consulta_dni.php', { dni: dni })
      .done(function (resp) {
        if (resp && !resp.error) {
          $('#apellido_paterno').val(resp.apellidoPaterno || '');
          $('#apellido_materno').val(resp.apellidoMaterno || '');
          $('#nombres').val(resp.nombres || '');
        } else {
          Swal.fire('Error', resp.error || 'No se encontró información', 'error');
        }
      })
      .fail(function () {
        Swal.fire('Error', 'No se pudo consultar el DNI', 'error');
      })
      .always(function () {
        $('#btnConsultarDni').prop('disabled', false);
      });
  });

  // editar
  $(document).on('click', '.editar', function () {
    let id = $(this).attr('id');
    $.post(base_url + 'assets/ajax/trabajadores.php?op=buscar', { id: id }, function (data) {
      if (!data) { Swal.fire('Error', 'Registro no encontrado', 'error'); return; }
      $('#modalTrabajador').modal('show');
      $('#idpersonal').val(data.idpersonal);
      $('#cliente_id').val($('#clientes').val() || '');
      $('#dni').val(data.dni);
      $('#tipo_doc').val(data.tipo_doc);
      $('#apellido_paterno').val(data.apellido_paterno);
      $('#apellido_materno').val(data.apellido_materno);
      $('#nombres').val(data.nombres);
      $('#direccion').val(data.direccion);
      $('#sexo').val(data.sexo);
      $('#estado_civil').val(data.estado_civil);
      $('#fecha_nacimiento').val(data.fecha_nacimiento);
      $('#nacionalidad').val(data.nacionalidad);
      $('#telefono').val(data.telefono);
      $('#movil').val(data.movil);
      $('#email').val(data.email);
      $('#lib_militar').val(data.lib_militar);
      $('#n_hijos').val(data.n_hijos);
      $('#grado_inst').val(data.grado_inst);
      $('#ocupacion').val(data.ocupacion);
      $('#prof_titulo').val(data.prof_titulo);
      $('#estado_laboral').val(data.estado_laboral);
      $('#tipo_personal').val(data.tipo_personal);
      $('#area').val(data.area);
      $('#departamento').val(data.departamento);
      $('#empresa_prestadora').val(data.empresa_prestadora);
      $('#turno').val(data.turno);
      $('#bono_extra').val(data.bono_extra);
      $('#cargo').val(data.cargo);
      $('#cuenta_bancaria').val(data.cuenta_bancaria);
      $('#cuspp').val(data.cuspp);
      $('#fecha_ingreso').val(data.fecha_ingreso);
      $('#fecha_contrato').val(data.fecha_contrato);
      $('#fecha_cese').val(data.fecha_cese);
      $('#basico').val(data.basico);
      $('#asignacion_familiar').val(data.asignacion_familiar);
      $('#moneda').val(data.moneda);
      $('#tipo_pago').val(data.tipo_pago);
      $('#essalud_regimen').val(data.essalud_regimen);
      $('#afp_fecha_inscripcion').val(data.afp_fecha_inscripcion);
      $('#banco_cts').val(data.banco_cts);
      $('#cuenta_cts').val(data.cuenta_cts);
      $('#cuenta_cts_usd').val(data.cuenta_cts_usd);
      $('#seguro_autogenerado').val(data.seguro_autogenerado);
      $('#ruc_eps').val(data.ruc_eps);
      $('#cci').val(data.cci);
      $('#cta_soles').val(data.cta_soles);
      $('#cta_dolares').val(data.cta_dolares);
      $('#senati').prop('checked', data.senati == 1);
      $('#afecto_impuesto').prop('checked', data.afecto_impuesto == 1);
      cargarSelects();
      // después de cargar selects elegir los valores
      setTimeout(function () {
        $('#idbanco').val(data.idbanco);
        $('#idafp').val(data.idafp);
        $('#idcentro').val(data.idcentro);
        $('#idcategoria').val(data.idcategoria);
        $('#idcontrato_tipo').val(data.idcontrato_tipo);
        $('#tipo_comision').val(data.tipo_comision);
        $('#chk_afp_mixta').prop('checked', (data.tipo_comision || '').toLowerCase() === 'mixta');
      }, 300);
    }, 'json');
  });

  // borrar / toggle estado
  $(document).on('click', '.borrar', function () {
    let id = $(this).attr('id');
    Swal.fire({ title: '¿Cambiar estado del trabajador?', icon: 'warning', showCancelButton: true }).then((r) => {
      if (r.isConfirmed) {
        $.post(base_url + 'assets/ajax/trabajadores.php?op=eliminar', { id: id }, function (res) {
          Swal.fire('OK', res.respuesta, 'success');
          tabla.ajax.reload();
        }, 'json');
      }
    });
  });

  // cargar clientes en combo y construir tabla
  $.ajax({
    url: base_url + "/assets/ajax/bancos.php?op=clientes",
    type: "POST",
    dataType: "json",
    success: function (data) {
      let opciones = '<option value="">--SELECCIONE CLIENTE--</option>';
      $.each(data, function (i, item) {
        opciones += `<option value="${item.id}">${item.ruc} - ${item.razon}</option>`;
      });
      $("#clientes").html(opciones);
      // iniciar tabla con el primer cliente si existe
      const firstId = (data && data.length) ? data[0].id : '';
      $('#clientes').val(firstId);
      initTabla(firstId);
    },
    error: function () {
      initTabla('');
    }
  });

  $("#clientes").on("change", function () {
    initTabla($(this).val());
  });

  $(document).on('change', '#chk_afp_mixta', function () {
    $('#tipo_comision').val(this.checked ? 'Mixta' : 'Flujo');
  });

});
