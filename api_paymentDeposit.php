<?php
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == "getInformation"){
    $bookingID = intval($request_data -> bookingID);

    // check in payment table
    $sql = "SELECT *
            FROM payment p, bookingdetail b
            WHERE p.bookingDetailID = b.bookingDetailID AND
                b.BookingID = $bookingID
            ";
    
    $query = $connect->query($sql);
        
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }

    if($query->rowCount() == 0)
    {
        $sql = "SELECT c.customerID,firstName,lastName,phone
            FROM customer c, booking b
            WHERE c.customerID = b.CustomerID AND
                b.BookingID = $bookingID
            ";

        $query = $connect->query($sql);
            
        while($row = $query -> fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }

        if($query->rowCount() == 0){
            $data = "1" ;
        }

    } 
    else{
        $data = "2";
    }
    echo json_encode($data);  
    
}

if($request_data->action=="getPayment"){
    $bookingID = intval($request_data -> bookingID);

    $sql ="SELECT b.roomID,r.roomType,DATEDIFF( b.checkOut, b.checkIn) AS day,b.price
            FROM bookingdetail b, hotelroom h, roomdescription r
            WHERE b.roomID = h.roomID AND
                h.roomTypeID = r.roomTypeID AND
                b.bookingID = $bookingID AND b.status = 'R'";

    $query = $connect->query($sql);
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }

    if($query->rowCount() == 0){
        $data = "" ;
    }
    echo json_encode($data);  

}

if($request_data->action=="confirmInf"){
    $bookingID = intval($request_data -> bookingID);
    $methodID = intval($request_data -> method);
    $date = $request_data -> date;
    
    $sql = "SELECT b.bookingDetailID,( b.price * 20 /100) AS amountPaid
            FROM bookingdetail b, hotelroom h, roomdescription r
            WHERE b.roomID = h.roomID AND
                h.roomTypeID = r.roomTypeID AND
                status = 'R' AND
                bookingID = $bookingID";

    $query = $connect->query($sql);
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }

    
    for($i = 0 ; $i < count($data) ; $i++){
        $bookingDetailID =  $data[$i]["bookingDetailID"];
        $amountPaid = $data[$i]["amountPaid"];
        $type = 1;
        
        $sql = "INSERT INTO payment (bookingDetailID,methodID,amountPaid,type,datePaid)
                VALUES($bookingDetailID,$methodID,$amountPaid,$type,'$date' )
                ";
                
        $query = $connect->query($sql);
        
        if($query){
            $out['success'] = true;
            $out['message'] = "Payment Successful";
          }
        else{
            $out['success'] = false;
            $out['message'] = "Payment was not Successful";
        }
        
    }
     echo json_encode($out);  
}

?>