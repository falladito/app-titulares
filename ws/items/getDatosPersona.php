<?php
header("Content-Type:application/json");
include_once("../db_connect.php");
if(!empty($_GET['name'])) {
	$name=$_GET['name'];
	$items = getItems($name, $conn);	
	if(empty($items)) {
		jsonResponse(200,"Items Not Found",NULL);
	} else 	{
		jsonResponse(200,"Item found",$items);
	}	
} else {
	jsonResponse(400,"Invalid request",NULL);
}
function jsonResponse($status,$status_message,$data) {
	header("HTTP/1.1 ".$status_message);	
	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;	
	$json_response = json_encode($response);
	echo $json_response;
}             
function getItems($name, $conn) {	
	$sql = "SELECT distinct
			LEFT(programa.mes_alta, 4) as mes_alta_anio,
			RIGHT(programa.mes_alta, 2) as mes_alta_mes,
			LEFT(programa.periodo, 4) as periodo_anio,
			RIGHT(programa.periodo, 2) as periodo_anio,
			LEFT(programa.ultimo_subsidio, 4) as ultimo_subsidio_anio,
			RIGHT(programa.ultimo_subsidio, 2) as ultimo_subsidio_mes,
			programa.mes_alta,
			programa.aviso,
			programa.fecha_estado,
			programa.monto,
			programa.periodo,
			programa.programa_id,
			programa_listado.programa_descripcion,
			programa.numero_formulario,
			persona.apellido,
			persona.fecha_nacimiento,
			persona.nombres,
			persona.dni,
			persona.cuit,
			programa.ultimo_subsidio,
			programa_estado.estado,
			sucursales_bna.sucursal,
			sucursales_bna.direccion,
			sucursales_bna.provincia,
			sucursales_bna.codigo_postal
			FROM
			programa JOIN persona 
			ON programa.id_persona = persona.id_persona
			JOIN programa_estado
			ON programa_estado.id = programa.programa_estado
			JOIN sucursales_bna
			ON persona.sucursal_bna = sucursales_bna.id
			JOIN programa_listado
			ON programa.programa_id = programa_listado.id
			WHERE persona.dni = '".$name."'";

	$resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
	$data = array();
	while( $rows = mysqli_fetch_assoc($resultset) ) {
		$data[] = $rows;
	}
	return $data;
}
?>