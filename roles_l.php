<?php

include ("conexion.php");

$op=$_REQUEST['op'];
$id=$_REQUEST['codigo'];
$nombre=$_REQUEST['nombre'];


if($op=='I')
{
	$dbmy->beginTrans();
	$q="insert into rol(nombre) values('$nombre')";
	//echo "<br>".$q;
	
	if(!$eq=$dbmy->Execute($q))
	{
		echo '<div data-ransa-server-response data-ransa-type="error" data-ransa-message="ERROR AL INSERTAR, POR FAVOR COMUNIQUE AL ADMINISTRADOR DEL SISTEMA"></div>';
		$dbmy->RollbackTrans();
		die();
		exit();
	}
	$dbmy->CommitTrans();	
}
if($op=='M')
{

	$dbmy->beginTrans();
	$q="update rol set nombre='$nombre' where id=$id";
	
	
	if(!$eq=$dbmy->Execute($q))
	{
		echo '<div data-ransa-server-response data-ransa-type="error" data-ransa-message="ERROR AL MODIFICAR, POR FAVOR COMUNIQUE AL ADMINISTRADOR DEL SISTEMA"></div>';
		$dbmy->RollbackTrans();
		die();
		exit();
	}
	$dbmy->CommitTrans();	
}
if($op=='E')
{
	$q="select * from rol_aplicaciones where cod_rol='$id'";
	$eq=$dbmy->Execute($q);
	$fq=$eq->RecordCount();
	
	$q1="select * from rol_usuarios where cod_rol='$id'";
	$eq1=$dbmy->Execute($q1);
	$fq1=$eq1->RecordCount();
	
	if($fq==0 and $fq1==0)
	{
		$dbmy->beginTrans();
		$q="delete from rol where id=$id";
		
		if(!$eq=$dbmy->Execute($q))
		{
			echo '<div data-ransa-server-response data-ransa-type="error" data-ransa-message="ERROR AL ELIMINAR, POR FAVOR COMUNIQUE AL ADMINISTRADOR DEL SISTEMA"></div>';
			$dbmy->RollbackTrans();
			die();
			exit();
		}
		$dbmy->CommitTrans();
	}
	else
	{
		echo '<div data-ransa-server-response data-ransa-type="warning" data-ransa-message="Existen aplicaciones y usuarios asociados por favor modifique y vuelva a intentarlo"></div>';
		exit();
	}	
	
		
}

echo '<div data-ransa-server-response data-ransa-type="success" data-ransa-message="Transacción exitosa" data-ransa-reload="roles.php" data-ransa-params="{}"></div>';
?>