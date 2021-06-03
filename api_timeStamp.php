<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == 'timeStamp'){
    $employeeID = intval($request_data -> employeeID) ;
    $type = $request_data -> type;
    
    $sql = "SELECT shift FROM `employee` WHERE employeeID = $employeeID";
    $query = $connect->query($sql);
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
        $shift =$row["shift"];
    }

    if($shift == 1){
        $sql = "SELECT  CAST(CURRENT_TIMESTAMP as time) >= '03:00:00' AND 
                CAST(CURRENT_TIMESTAMP as time) < '05:00:00' AS checklate";
    }
    else if($shift == 2){
        $sql = "SELECT  CAST(CURRENT_TIMESTAMP as time) >= '11:00:00' AND 
                CAST(CURRENT_TIMESTAMP as time) < '13:00:00' AS checklate";
    }
    else if($shift == 3){
        $sql = "SELECT  CAST(CURRENT_TIMESTAMP as time) >= '19:00:00' AND 
                CAST(CURRENT_TIMESTAMP as time) < '21:00:00' AS checklate";
    }
    $query = $connect->query($sql);
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
        $checklate =$row["checklate"];
    }

    $checklate = intval($checklate);

    $sql = "INSERT INTO timestamp (employeeID,type,late)
            VALUES('$employeeID','$type','$checklate')";

    $query = $connect->query($sql);   
    
    if($query){
        $data["success"] = true;
    }
    else{
        $data["success"] = false;
    }

    echo json_encode($data);
}


?>