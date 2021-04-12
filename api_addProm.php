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
        $seasonID = $request_data -> seasonID;
        $roomTypeID = $request_data -> roomTypeID;
        $promotionName = $request_data -> promotionName;
        $startDate = $request_data -> startDate;
        $endDate = $request_data -> endDate;
        $discount = $request_data -> discount;

        if($seasonID=='') {
            $out['seasonID'] = true;
            $out['message'] = "SeasonID is required";
          }
    
          else if ($roomTypeID=='') {
            $out['roomTypeID'] = true;
            $out['message'] = "Room Type is required";
          }
    
          else if($promotionName=='') {
            $out['promotionName'] = true;
            $out['message'] = "Salary Name is required";
          }

          else if($startDate=='') {
            $out['startDate'] = true;
            $out['message'] = "Start Date is required";
          }

          else if($endDate=='') {
            $out['endDate'] = true;
            $out['message'] = "End Date is required";
          }
    
          else if($discount =='') {
            $out['discount'] = true;
            $out['message'] = "Discount is required";
          }
    
          else if ((is_numeric($discount) == false)) {
            $out['discount'] = true;
            $out['message'] = "Discount is not correct";  
          }

          else {
            $seasonID = intval($seasonID);
            $roomTypeID = intval($roomTypeID);
            $promotionName = ucfirst($promotionName);
            $discount = floatval($discount); 
            
            //Query RoleID
            $sql_promID = "SELECT MAX(promotionID) AS promotionID FROM promotion";
            $query = $connect->query($sql_promID);
            while($row = $query -> fetch(PDO::FETCH_ASSOC)){
                $data[] = $row;
            }

            //Set EmployeeID
            if($data[0]["promotionID"] == 0){
                $promotionID = ($promotionID + 1);
            }
            else{
                $promotionID = $data[0]["promotionID"] + 1;
            }
            $sql = "INSERT INTO promotion
              (promotionID, seasonID ,roomTypeID,promotionName, startDate, endDate, discount) 
              VALUES ('$promotionID','$seasonID' ,'$roomTypeID','$promotionName', '$startDate', '$endDate','$discount')";
            $query = $connect->query($sql);

            if($query){
                $out['message'] = "User Added Successfully";
            }
            else{
                $out['error'] = true;
                $out['message'] = "Could not add User";
            }
          }
            echo json_encode($out);
     
}
?>