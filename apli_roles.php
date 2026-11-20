<?php
include ("conexion.php");

$cod_usu='';
$cod_usu=$_REQUEST['rol'];

?>

<form name=form id=form action="" method="post" onsubmit="return false;">
<div class="row">
<div class ="col-md-12">
<i><h3><?php ECHO htmlentities('ASOCIACIÓN USUARIOS A ROLES');?></h3></i>
</div>
</div>
<div class="row">
<div class="col-md-2"></div>
<div class="col-md-3">
<label><?php echo htmlentities('Seleccione del Usuario');?></label>
	<input type=hidden name=op id=op>
</div>
<div class="col-md-5">
	
	<select name=rol id=rol required class="form-control">
	<option value=''>-Seleccione-</option>
	<?php
	$query="select * from usuarios where estado=1 order by nombre";
	$equery=$dbmy->Execute($query);
	$fquery=$equery->RecordCount();
	for ($i=0;$i<$fquery;$i++){
	
		$codigo=$equery->fields['id'];
		$nombre=$equery->fields['nombre'];
		$apellido=$equery->fields['apellido'];
		$datos=$nombre.' '.$apellido;
	
		if($cod_usu==$codigo)
		{
			echo "
			<option value='$codigo' selected>".utf8_encode($datos)."</option>
			";
		}
		else
		{
			echo "
			<option value='$codigo'>".utf8_encode($datos)."</option>
			";
		}
		
		
		$equery->MoveNext();
	}
	?>
	</select>
	</div>
<div class="col-md-2"></div>
</div>
<div class ="row">	
<div  class="col-xs-12 col-md-12">
<button type=submit name=bt1 id=bt1 value='Consultar' title='De clic para Consultar' onclick='consultar();' class="btn btn-primary color_botton1"><i class="fa fa-search"  aria-hidden="true" style="font-size: 18px;"></i> &nbsp; Consultar</button>
</div>
</div>


<?php
if($cod_usu!='')
{
?>
	<div class ="row">	
	<div  class="col-xs-12 col-md-12">
	<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Buscar por nombre.." class="form-control">
	</div>

	<div  class="col-xs-12 col-md-12">
	<table  id="myTable" class="table-responsive" WIDTH=100% border=1 cellspacing=0 cellspading=0>
		<tr class="header">
			<th align=center >#</th>
			<th align=center>NOMBRE DEL ROL</th>
			<th align=center>OP</th>
		</tr>	

	<?php

	$q1="select id,nombre from rol order by nombre ";
	$eq1=$dbmy->Execute($q1);
	$fq1=$eq1->RecordCount();

	for ($i=0;$i<$fq1;$i++){
		
		$j=$i+1;	
		$codigo_b=$eq1->fields['id'];
		$nombre_b=$eq1->fields['nombre'];
		
		$q="select * from rol_usuarios  where cod_rol=$codigo_b and cod_usuario=$cod_usu";
		$eq=$dbmy->Execute($q);
		$fq=$eq->RecordCount();
	?>
	<tr class="active">
		<td ><?php  echo $j;?></td>
		<td  ><?php echo $nombre_b;?></td>
		<?php
		if($fq==0)
		{
		?>
			<td >
			<input type='checkbox' name='ck<?php echo $j;?>' id='ck<?php echo $j;?>' value='<?php echo $codigo_b;?>' onclick="actualizar('ck<?php echo $j;?>',<?php echo $cod_usu;?>,<?php echo $codigo_b;?>);">
			</td>
		<?php
		}
		else
		{
		?>
			<td >
			<input type='checkbox' name='ck<?php echo $j;?>' id='ck<?php echo $j;?>' value='<?php echo $codigo_b;?>' onclick="actualizar('ck<?php echo $j;?>',<?php echo $cod_usu;?>,<?php echo $codigo_b;?>);" checked>
			</td>
		<?php
		}
		?>	
	</tr>
	
	<?php	
		$eq1->MoveNext();
	}	
	?>
		<tr>
			<th colspan=3><?php echo $j.' Registros existentes'?></th>
		</tr>
</table>
</div>
</div>
<?php
}
?>	
	
	
</form>


