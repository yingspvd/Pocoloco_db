<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == "getServiceActivity"){
    
    $sql = "SELECT *
            FROM serviceactivity_view
            ORDER BY date DESC";
                    
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    
    if($query->rowCount() == 0)
    {
        $data = "";
    }
    
    echo json_encode($data);   
} 

if($request_data -> action == "getServiceData"){
    
    $roomID = intval($request_data -> roomID) ;
    $date = $request_data -> date;
    
    $sql = "SELECT CAST(DateTime As date) AS date,roomID,s.name,r.amount,s.servicePrice
            FROM roomservice r,servicelist s
            WHERE r.serviceID = s.serviceID AND
                    roomID = '$roomID' AND
                    DateTime LIKE '$date%' AND
                    type = 2
            ORDER BY DateTime
            ";
                    
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    
    if($query->rowCount() == 0)
    {
        $data = "";
    }
    
    echo json_encode($data);   
} 

if($request_data->action == 'searchActivity')
{
    $search = $request_data->search;
    $filter = $request_data->filter;
    $sort = $request_data->sort;
    $direction = $request_data->direction;

    if($direction == "up"){
        $sql = "SELECT *
            FROM serviceactivity_view
            WHERE $filter LIKE '$search%'
            ORDER BY $sort DESC";
    }
    else if($direction == "down"){
        $sql = "SELECT *
            FROM serviceactivity_view
            WHERE $filter LIKE '$search%'
            ORDER BY $sort";
    }
    else{
        $sql = "SELECT *
            FROM serviceactivity_view
            ORDER BY date DESC";
    }
    
    
    $query = $connect->query($sql);
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }

    if($query->rowCount() == 0)
    {
        $data = "";
    }
  
    echo json_encode($data);
}
?>