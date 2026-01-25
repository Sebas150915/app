<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= nombre() ?></title>
<link rel="stylesheet" href="<?= base_url() ?>/assets/css/styles.css?v=0" />
<link rel="shortcut icon" type="image/png" href="<?= media() ?>/images/logos/icono.png" />


<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

<!-- Font Awesome -->
<link rel="stylesheet" href="<?= media() ?>/plugins/fontawesome.css">

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.23.0/sweetalert2.css"
    integrity="sha512-/j+6zx45kh/MDjnlYQL0wjxn+aPaSkaoTczyOGfw64OB2CHR7Uh5v1AML7VUybUnUTscY5ck/gbGygWYcpCA7w=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- ✅ DataTables + Buttons CSS con versión correcta -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.5/css/buttons.dataTables.min.css">


<style>
    .sidebar-link,
    .hide-menu {
        color: white;
    }

    .left-sidebar {
        background-color: #0A71BF;
    }

    span {
        color: white;
    }

    .modal-title,
    .card-title {
        color: white;
        font-weight: bold;
    }

    .card-header {
        background-color: black;
    }

    .dt-buttons {
        text-align: center;
        margin-bottom: 15px;
    }

    .dt-button {
        margin: 0 5px !important;
        border-radius: 6px !important;
        color: #fff !important;
        border: none !important;
        padding: 6px 15px !important;
        font-weight: bold !important;
    }


    .dt-search {
        margin-bottom: 10px;
    }


    .secondary {
        background-color: #057c9d !important;
    }

    .info {
        background-color: #ed7e08 !important;
    }

    .success {
        background-color: #36ba11 !important;
    }

    .danger {
        background-color: #e50808 !important;
    }

    .warning {
        background-color: #e3e92c !important;
    }

    .dt-nowrap {
        background-color: #d7dbd7 !important;
    }




    .dark {
        background-color: #057c9d !important;
    }


    table.dataTable thead>tr>th.dt-orderable-asc,
    table.dataTable thead>tr>th.dt-orderable-desc,
    table.dataTable thead>tr>th.dt-orderable-none {
        background-color: black !important;
        color: white;
    }


    .body-wrapper>.container-fluid {
        max-width: 95% !important;
    }





    < !-- Custom Modern Styles --><link rel="stylesheet" href="<?= base_url() ?>/assets/css/custom_modern.css?v=<?= time() ?>" />
</style>