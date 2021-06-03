<?php
require_once 'connect.php';

$request_data = json_decode(file_get_contents("php://input"));

if($request_data->action == 'getAll') {
    $type = intval($request_data -> type);
    
    $sql = "SELECT * FROM servicelist_view 
            WHERE TYPE = $type AND
            STATUS = 1";
            
    $query = $connect->prepare($sql);
    $query->execute();
    while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    if($query->rowCount() == 0)
    {
        $data = "";
    }

    echo json_encode($data);
}

if($request_data->action == 'finishOrderChef')
{
    $DATETIME = $request_data->DATETIME;
    $roomID = $request_data->roomID;
    $serviceID = $request_data->serviceID;
    $serviceName = $request_data->serviceName;
    $STATUS = $request_data->STATUS;
    $type = intval($request_data -> type);

    $sql = "UPDATE roomservice 
                SET status = 2
                WHERE DATETIME = '$DATETIME' AND serviceID = '$serviceID' AND roomID = '$roomID' 
                ";

    $query = $connect->query($sql);

    if($query){
        $out['message'] = "Updated Successfully";
        $out['success'] = true;
        }
        else{
        $out['message'] = "Could not update ";
        }

    echo json_encode($out); 
}
?>