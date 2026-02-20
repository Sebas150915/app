<?php

/**
 * Configuración principal de la aplicación
 */

// Configuración de la URL base
//const BASE_URL = "https://appsiscont.smartbase.club/";
const BASE_URL = "http://localhost/app/";
// Configuración de zona horaria
const TIMEZONE = 'America/Lima';
date_default_timezone_set(TIMEZONE);

/**
 * Configuración de la base de datos
 * TODO: Mover estas credenciales a un archivo .env
 */
const BD_HOST     = "161.132.39.31";
const BD_NAME     = "aplicativo";
const BD_USER     = "aplicativo";
const BD_PASSWORD = "aplicativo";
const BD_CHARSET  = "charset=utf8";

/**
 * Configuración de la empresa
 */
const NOMBRE    = "GESTIA ERP";
const LOGO      = "APP GESTIA";
const CORTO     = "G";
const EMPRESA   = "J Y M SMART SOLUTIONS S.A.C.";

/**
 * Configuración de formato numérico
 */
const DECIMAL_SEPARATOR = ".";  // Separador decimal
const THOUSAND_SEPARATOR = ","; // Separador de miles

/**
 * Configuración de Herramientas del Sistema (Binarios)
 * Dejar vacío para intentar detección automática en el PATH del sistema
 * Ejemplos:
 * Windows: "C:\\Program Files\\qpdf\\bin\\qpdf.exe" o "C:\\Program Files\\gs\\gs10.04.0\\bin\\gswin64c.exe"
 * Linux: "/usr/bin/qpdf" o "/usr/bin/gs"
 */
const PDF_TOOL_PATH = ""; // Ruta completa al ejecutable si no está en el PATH
