<?php
header("Content-Type:application/json");
include_once("../db_connect.php");
if(!empty($_GET['name'])) {
	$name=$_GET['name'];
	$items = getItems($name, $conn);	
	if(empty($items)) {
		jsonResponse(200,"Items Not Found",NULL);
	} else 	{
		jsonResponse(200,"Item Found",$items);
	}	
} else {
	jsonResponse(400,"Invalid Request",NULL);
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
	//$sql = "SELECT id, p.name, p.description, p.price, p.created FROM items p WHERE p.name LIKE '%".$name."%' ORDER BY p.created DESC";
	$sql = "select
ticket.number as caso,
topic.topic as tematica,
apellido.value as apellido,
thread.title as motivo,
ticket.created as fecha_creacion,
team.name as asignado_a,
case
	   		when ticket.status_id = 1 then 'Abierto'
	   		when ticket.status_id = 2 then 'Resuelto'
	   	    when ticket.status_id = 3 then 'Cerrado'
	   	    when ticket.status_id = 4 then 'Archivado'
	   	    when ticket.status_id = 5 then 'Elimintado'
	   	    when ticket.status_id = 6 then 'No contactado'
	   		else 'Estado indeterminado'
	   		end as estado
from
ost_ticket ticket
JOIN 
ost_help_topic as topic on ticket.topic_id = topic.topic_id
join
ost_ticket_thread as thread on ticket.ticket_id = thread.ticket_id
join
ost_team as team on ticket.team_id = team.team_id
JOIN 
(
select * from
  ost_form_entry fe,
  ost_form_entry_values fev
  where
  fe.id = fev.entry_id and
    fe.form_id= 13 and fev.field_id = 48 
) as apellido on apellido.object_id = ticket.ticket_id
and
thread.user_id <> 0
and
ticket.number = ".$name."";
	$resultset = mysqli_query($conn, $sql) or die("database error:". mysqli_error($conn));
	$data = array();
	while( $rows = mysqli_fetch_assoc($resultset) ) {
		$data[] = $rows;
	}
	return $data;
}
?>