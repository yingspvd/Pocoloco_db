<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == 'timeStamp'){
    $employeeID = intval($request_data -> employeeID) ;
    $type = $request_data -> type;
    
    $sql = "INSERT INTO timestamp (employeeID,type)
            VALUES('$employeeID','$type')";

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