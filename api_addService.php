<?php
require_once 'connect.php';

$request_data = json_decode(file_get_contents("php://input"));

if($request_data -> action == "searchService"){

    $search = $request_data -> search;
    
    $sql = "SELECT serviceID, name, servicePrice
            FROM servicelist
            WHERE name LIKE '$search%' ";
                    
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
    $roomID = $request_data -> roomID;


    $sql = "INSERT INTO roomservice(roomID,serviceID,amount,total)
            VALUES ('$roomID','$serviceID','$amount','$total')";
                    
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }

    if($query){
        $out['message'] = "Added Successfully";
      }
      else{
        $out['message'] = "Could not add";
      }
    
     echo json_encode($out);  
}
?>