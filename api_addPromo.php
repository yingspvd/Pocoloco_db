<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));
$data = array();

// Check Error
$out = array(  'error' => false, 
                'seasonID' => false,
                'roomTypeID' => false,
                'promotionName' => false,
                'startDate' => false,
                'endDate' => false,
                'discount' => false);

if($request_data -> action == "getRoomType"){

  // query
  $sql = "SELECT roomTypeID,roomType FROM roomdescription";
  $query = $connect->query($sql);
  
  while($row = $query -> fetch(PDO::FETCH_ASSOC)){
      $data[] = $row;
  }

  echo json_encode($data);
}

if($request_data -> action == "getSeason"){

  // query
  $sql = "SELECT seasonID,seasonName FROM season";
  $query = $connect->query($sql);
  
  while($row = $query -> fetch(PDO::FETCH_ASSOC)){
      $data[] = $row;
  }

  echo json_encode($data);
}
                
if($request_data-> action == "addPromotion")
{
    // From User
        $seasonID = intval($request_data -> seasonID);
        $roomTypeID = intval($request_data -> roomTypeID);
        $promotionName = ucfirst($request_data -> promotionName);
        $startDate = $request_data -> startDate;
        $endDate = $request_data -> endDate;
        $discount = floatval($request_data -> discount);


        $sql = "INSERT INTO promotion
          (seasonID ,roomTypeID,promotionName, startDate, endDate, discount) 
          VALUES ('$seasonID' ,'$roomTypeID','$promotionName', '$startDate', '$endDate','$discount')";
        $query = $connect->query($sql);

        if($query){
            $out['message'] = "Promotion Added Successfully";
            $out['success'] = true;
        }
        else{
            $out['message'] = "Could not add promotion";
        }
      
      echo json_encode($out);
     
}
?>