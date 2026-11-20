<?php
session_start();

error_reporting(E_ALL);

include ("conexion_sin.php");

$us = isset($_REQUEST['us']) ? trim($_REQUEST['us']) : '';
$pa = isset($_REQUEST['pa']) ? trim($_REQUEST['pa']) : '';

$us_clean = addslashes($us);
$pa_clean = addslashes($pa);

$q = "select * from usuarios where usuario='$us_clean' and clave='$pa_clean'";
$eq = $dbmy->Execute($q);

if ($eq && $eq->RecordCount() > 0)
{
	$codigo = $eq->fields['id'];
	$nombre = $eq->fields['nombre'];
	$nit = $eq->fields['nit'];
	$email = $eq->fields['email'];
	
	$_SESSION["id"] = $codigo;
	$_SESSION["nombre"] = $nombre;
	$_SESSION["nit"] = $nit;
	$_SESSION["email"] = $email;
	
	$_SESSION["autentificado"] = "SI";
	$_SESSION["ultimoAcceso"] = date("Y-n-j H:i:s");
	
	header('Location: contexto.php');
	exit;
}
else
{
	header('Location: login.html?status=error');
	exit;
}
?>