<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == "getRoomNumber"){

    $building = intval($request_data -> building);
    $floor = intval($request_data -> floor);
    $date = $request_data -> date;
       
    // query
    $sql = "SELECT h.roomID,r.roomType,
                CASE
                WHEN  b.status = 'O' THEN 1
                WHEN  b.status = 'C' THEN 1
                WHEN  b.status = 'I' THEN 0
                WHEN  b.status = 'R' THEN 0 
                ELSE  1
                END AS status
            FROM hotelroom h LEFT JOIN bookingdetail b ON h.roomID = b.roomID AND ('$date' BETWEEN b.checkIn AND b.checkOut)
            LEFT JOIN roomdescription r ON h.roomTypeID = r.roomTypeID 
            WHERE h.roomID LIKE '$building$floor%'";
                
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
  
    echo json_encode($data);
  }

?>