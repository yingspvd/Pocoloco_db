<?php
require_once 'connect.php';

$request_data = json_decode(file_get_contents("php://input"));

if($request_data->action == 'getAllService')
{
    $sql="SELECT * FROM service_view 
            WHERE type LIKE 'Room Facilities'
            ORDER BY serviceID
        ";
    $query = $connect->query($sql);
        
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }

    echo json_encode($data);   
}

if($request_data -> action == "searchService"){

    $search = $request_data -> search;
    
    $sql = "SELECT serviceID, name, servicePrice
            FROM servicelist
            WHERE name LIKE '$search%' AND type LIKE 'Room Facilities' ";
                    
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    
    echo json_encode($data);  
}

if($request_data -> action == "confirmService"){

    $serviceID = $request_data -> serviceID;
    $amount = $request_data -> amount;
    $total = $request_data -> total;
    $roomID = intval($request_data -> roomID);

    // Check have RoomID
    $sql = "SELECT roomID 
            FROM hotelroom
            HAVING roomID = $roomID";
    $query = $connect->query($sql);
    
    if($query-> rowCount() == 1){
        $sql = "INSERT INTO roomservice(roomID,serviceID,amount,total)
            VALUES ('$roomID','$serviceID','$amount','$total')";
                    
        $query = $connect->query($sql);

        if($query){
            $out['success'] = true;
            $out['message'] = "Added Successfully";
        }
        else{
            $out['message'] = "Could not add";
        }
    }
    else{
        $out['success'] = false;
        $out['message'] = "Don't have room ID $roomID" ;
    }

     echo json_encode($out);  
}
?>