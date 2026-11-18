<?php
//error_reporting(0);

error_reporting(E_ALL);

// Ocultar avisos de obsolescencia (Deprecated)
//error_reporting(E_ALL & ~E_DEPRECATED);
//ini_set('display_errors', 1);

include ("bases_datos/adodb/adodb.inc.php");
include ("bases_datos/usb_defglobales.inc");

$dbmy = NewADOConnection("mysqli");
$dbmy->Connect($bd_host,$bd_usuario,$bd_password,$bd_base);
if (!$dbmy){
       echo "<br>error de conexion<br>";
       exit;
}



?>