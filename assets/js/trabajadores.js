$(document).ready(function () {
  let tabla = $('#tblTrabajadores').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: base_url + "assets/ajax/trabajadores.php?op=listar",
      type: "POST"
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

  // cargar selects al abrir modal
  $('#botonCrear').click(function () {
    $('#formTrabajador')[0].reset();
    $('#idpersonal').val(0);
    $('.modal-title').text('Nuevo Trabajador');
    cargarSelects();
  });

  function cargarSelects() {
    $.post(base_url + 'assets/ajax/trabajadores.php?op=selects', {}, function (resp) {
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

  // editar
  $(document).on('click', '.editar', function () {
    let id = $(this).attr('id');
    $.post(base_url + 'assets/ajax/trabajadores.php?op=buscar', { id: id }, function (data) {
      if (!data) { Swal.fire('Error', 'Registro no encontrado', 'error'); return; }
      $('#modalTrabajador').modal('show');
      $('#idpersonal').val(data.idpersonal);
      $('#dni').val(data.dni);
      $('#apellido_paterno').val(data.apellido_paterno);
      $('#apellido_materno').val(data.apellido_materno);
      $('#nombres').val(data.nombres);
      $('#cargo').val(data.cargo);
      $('#cuenta_bancaria').val(data.cuenta_bancaria);
      $('#cuspp').val(data.cuspp);
      $('#fecha_ingreso').val(data.fecha_ingreso);
      $('#fecha_cese').val(data.fecha_cese);
      $('#basico').val(data.basico);
      $('#asignacion_familiar').val(data.asignacion_familiar);
      cargarSelects();
      // después de cargar selects elegir los valores
      setTimeout(function () {
        $('#idbanco').val(data.idbanco);
        $('#idafp').val(data.idafp);
        $('#idcentro').val(data.idcentro);
        $('#idcategoria').val(data.idcategoria);
        $('#idcontrato_tipo').val(data.idcontrato_tipo);
        $('#tipo_comision').val(data.tipo_comision);
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

});
