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

    // Check Promotion Date
    $dataCheck = $startDate;
    while($dataCheck <= $endDate){
        $sql = "SELECT *
                FROM promotion_view
                WHERE ('$dataCheck' BETWEEN startDate AND endDate) AND 
                roomTypeID = $roomTypeID ";
                
        $query = $connect->query($sql);
        while($row = $query -> fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }
        
        $dataCheck = date('Y-m-d',strtotime($dataCheck. '1 days'));
      }
    
    if($query->rowCount() == 0){
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
      
    }
    else{
      $out['message'] = "Already have this promotion during this date";
    }

  echo json_encode($out);
     
}
?>