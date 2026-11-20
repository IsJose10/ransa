<?php

include ("conexion.php");

$clave_actual = $_REQUEST['clave_actual'];
$clave_nueva = $_REQUEST['clave_nueva'];
$id = $_SESSION["id"];

// Verificar contraseña actual
$q = "select * from usuarios where id=$id";
$eq = $dbmy->Execute($q);

if(!$eq || $eq->RecordCount() == 0)
{
	echo '<div data-ransa-server-response data-ransa-type="error" data-ransa-message="ERROR: NO SE ENCONTRÓ EL USUARIO, POR FAVOR COMUNIQUE AL ADMINISTRADOR DEL SISTEMA"></div>';
	die();
	exit();
}

$clave_db = $eq->fields['clave'];

if($clave_actual != $clave_db)
{
	echo '<div data-ransa-server-response data-ransa-type="error" data-ransa-message="La contraseña actual no es correcta." data-ransa-reload="cambio.php" data-ransa-params="{}"></div>';
	die();
	exit();
}

// Actualizar contraseña
$dbmy->beginTrans();
$q = "update usuarios set clave='$clave_nueva' where id=$id";

if(!$eq = $dbmy->Execute($q))
{
	echo '<div data-ransa-server-response data-ransa-type="error" data-ransa-message="ERROR AL ACTUALIZAR LA CONTRASEÑA, POR FAVOR COMUNIQUE AL ADMINISTRADOR DEL SISTEMA"></div>';
	$dbmy->RollbackTrans();
	die();
	exit();
}
$dbmy->CommitTrans();

echo '<div data-ransa-server-response data-ransa-type="success" data-ransa-message="Contraseña actualizada exitosamente." data-ransa-reload="cambio.php" data-ransa-params="{}"></div>';
?>
