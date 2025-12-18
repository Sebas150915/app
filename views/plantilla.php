<?php
session_start();
//error_reporting(0);
//echo 'sesion :'.$_SESSION["iniciarSesion"];
if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "cinema") {
  $rutas = array();
  /*=============================================
        CONTENIDO
        =============================================*/
  if (isset($_GET["ruta"])) {
    $rutas = explode("/", $_GET["ruta"]);

    if (
      $rutas[0] == "inicio" ||
      $rutas[0] == "centro_costos" ||
      $rutas[0] == "presupuestos" ||
      $rutas[0] == "concepto_gasto" ||
      $rutas[0] == "bancos" ||
      $rutas[0] == "locales" ||
      $rutas[0] == "clientes" ||
      $rutas[0] == "rpt_rendicion" ||
      $rutas[0] == "sire_compras" ||
      $rutas[0] == "honorarios" ||

      $rutas[0] == "planillas" ||
      $rutas[0] == "trabajadores" ||

      //procesos

      $rutas[0] == "detracciones" ||
      $rutas[0] == "rendiciones" ||
      $rutas[0] == "bancos_procesos" ||
      $rutas[0] == "rendicion_cab" ||
      $rutas[0] == "rendicion_det" ||
      $rutas[0] == "importar_banco" ||
      $rutas[0] == "cerrar"
    ) {

      include "modules/" . $rutas[0] . ".php";
    } else {

      include "modules/404.php";
    }
  } else {
    include "modules/inicio.php";
  }
} else {
  include "modules/login.php";
}
