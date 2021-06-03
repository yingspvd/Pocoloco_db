<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));
$out = array();

if($request_data -> action == "getBookingID"){
  
  // query
  $sql = "SELECT MAX(bookingID) AS bookingID FROM booking";
  $query = $connect->query($sql);
  
  while($row = $query -> fetch(PDO::FETCH_ASSOC)){
      $data[] = $row;
  }

  // Set BookingID
  if($data[0]["bookingID"] == ""){
    $bookingID = 1000000001;
  }
  else{
    $bookingID = $data[0]["bookingID"] + 1;
  }
  
  echo json_encode($bookingID);
}

if($request_data -> action == "getBookingDetail"){
  
  $bookingID = $request_data -> bookingID;
  
  // query
  $sql = "SELECT b.bookingDetailID,b.roomID,r.roomType
          FROM bookingdetail b,roomdescription r,hotelroom h
          WHERE b.roomID = h.roomID AND
              h.roomTypeID = r.roomTypeID AND
          b.bookingID = $bookingID
          GROUP BY b.bookingDetailID";
          
  $query = $connect->query($sql);
  
  if($query->rowCount() != 0){
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
      $data[] = $row;
    }
    echo json_encode($data);
  }
  
   
}

if($request_data->action == "deleteBookingdetail"){
  
  $bookingDetail = $request_data -> bookingDetail;
  
  $query = "DELETE FROM bookingdetail WHERE bookingdetailID = $bookingDetail";
  $statement = $connect -> prepare($query);
  $statement -> execute();
  $output['success'] = true;
  $output['message'] = "Delete Complete";
  echo json_encode($output);
}

if($request_data -> action == "addBooking"){
  
  // Input from USER
  $bookingID = $request_data -> bookingID;
  $customerID = $request_data -> customerID;

  $customerID = intval($customerID);

  // Check have CustomerID
  $sql = "SELECT customerID 
          FROM customer
          GROUP BY customerID
          HAVING customerID = $customerID";
  
  $query = $connect->query($sql);

  if($query->rowCount() == 1){
    // Add Booking to DB
    $sql = "INSERT INTO booking
    VALUES ('$bookingID','$customerID')";

    $query = $connect->query($sql);

    if($query){
      $out['message'] = "Added Successfully";
      $out['success'] = true;
    }
    else{
      $out['message'] = "Could not add";
    }
    
  }

  else{
    $out['message'] = "Don't have this customer ID";
  }
  
    
  echo json_encode($out);

}
?>