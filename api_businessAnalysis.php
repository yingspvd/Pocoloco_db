<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == "getTotalEarning"){

    $date = $request_data -> date;

    $sql = "SELECT sum(amountPaid) AS totalEarning
            FROM payment
            WHERE datePaid LIKE '$date%' AND type = 2";

   $query = $connect->query($sql);
            
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    echo json_encode($data);

}

if($request_data -> action == "getTotalBooking"){

    $date = $request_data -> date;

    $sql = "SELECT count(bookingDetailID) AS numBookingDetail
            FROM bookingdetail
            WHERE dateTime LIKE '$date%'";

   $query = $connect->query($sql);
            
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    echo json_encode($data);

}
if($request_data -> action == "getTotalOrder"){

    $date = $request_data -> date;

    $sql = "SELECT count(serviceID) AS numServiceID
            FROM roomservice
            WHERE DateTime LIKE '$date%'";

   $query = $connect->query($sql);
            
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    echo json_encode($data);

}




    

?>