<?php
/**
 *    Archivo con los datos de configuración
 */
date_default_timezone_set('Europe/Madrid');

//Definimos la raiz donde vamos a trabajar
if (!defined('RAIZ')) {
    define('RAIZ', dirname(__FILE__));
}
//Configuración de la base de datos
if (!defined('DBHOST')) {
    define('DBHOST', '89.248.100.45');
}

if (!defined('DBNOMBRE')) {
    define('DBNOMBRE', 'unicorndb');
}

if (!defined('DBUSUARIO')) {
    define('DBUSUARIO', 'adminzXatynAcyAh');
}

if (!defined('DBPASSWORD')) {
    define('DBPASSWORD', 'O@mDzoI73@fQ');
}

if (!defined('CARPETA')) {
    define('CARPETA', 'unicorn/');
}

if (!defined('APIURLPCCOMPONENTES')) {
define('APIURLPCCOMPONENTES','https://pccomponentes-prod.mirakl.net/api');
}
if (!defined('APIKEYPCCOMPONENTES')) {
define('APIKEYPCCOMPONENTES','34e564b3-4def-4dba-b94b-bac67c091709');
}

if (!defined('APIURLPHONEHOUSE')) {
define('APIURLPHONEHOUSE','https://phonehousespain-prod.mirakl.net/api');
}
if (!defined('APIKEYPHONEHOUSE')) {
define('APIKEYPHONEHOUSE','46a7b667-ddaa-4817-a386-f685a4a40796');
}
