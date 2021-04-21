<?php 
require_once 'connect.php';
$request_data = json_decode(file_get_contents("php://input"));

if($request_data -> action == "addNewService"){
    
    $serviceType = intval($request_data -> serviceType);
    $serviceName = $request_data -> serviceName;
    $price = floatval($request_data -> price);

    // query
    $sql = "INSERT INTO servicelist(type,name,servicePrice)
            VALUES ('$serviceType','$serviceName','$price')";
  
    $query = $connect->query($sql);

    if($query){
        $out['message'] = "Added Successfully";
        $out['success'] = true;
    }
    else{
        $out['message'] = "Could not add";
    }
      
    echo json_encode($out);
}
?>