<?php
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == "getInformation"){

    $roomID = $request_data -> roomID;
    $sql = "SELECT b.bookingID,c.customerID,c.firstName,c.lastName,c.phone
            FROM customer c,bookingdetail d,booking b
            WHERE d.bookingID = b.bookingID AND
                b.customerID = c.customerID AND
                d.status = 'I' AND
                d.roomID = $roomID
            ";
    
    $query = $connect->query($sql);
        
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }

    if($query->rowCount() == 0)
    {
        $data = "";
    }
    
    echo json_encode($data);  
    
}

    if($request_data -> action == "PaymentCheckOut"){

        $roomID = intval($request_data -> roomID);

        // Get Room Price
        $sql = "SELECT b.bookingID,b.roomID,r.roomType AS name,DATEDIFF( b.checkOut, b.checkIn) AS amount,b.price AS total
                FROM bookingdetail b,hotelroom h,roomdescription r
                WHERE b.roomID = h.roomID AND
                    h.roomTypeID = r.roomTypeID AND
                    b.status = 'I' AND
                    b.roomID = $roomID" ;
                    
        $query = $connect->query($sql);
        
        while($row = $query -> fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }

        $bookingID = $data[0]["bookingID"];
        $total = $data[0]["total"] * 20 / 100;
        
        // Get Date
        $sql = "SELECT checkIn,checkOut
                FROM bookingdetail 
                WHERE status = 'I' AND
                    roomID = $roomID ";
                    
        $query = $connect->query($sql);
        
        while($row = $query -> fetch(PDO::FETCH_ASSOC)){
            $checkIn = $row["checkIn"];
            $checkOut = $row["checkOut"];
        }
        
        // Get Service Price
        $dataCheck = $checkIn;
        while($dataCheck <= $checkOut){
            $sql = "SELECT b.bookingID,r.roomID,s.name,r.amount,r.total
                    FROM roomservice r, servicelist s, bookingDetail b
                    WHERE r.serviceID = s.serviceID AND
                        b.roomID = r.roomID AND
                        r.dateTime LIKE '$dataCheck%' AND
                        r.roomID = $roomID AND
                        b.status = 'I'";
                    
            $query = $connect->query($sql);
            while($row = $query -> fetch(PDO::FETCH_ASSOC)){
                $data[] = $row;
            }
            
            $dataCheck = date('Y-m-d',strtotime($dataCheck. '1 days'));
          }

        $deposit = array("bookingID"=>$bookingID,"roomID"=> "$roomID", "name" => "Deposit","amount" => "1" ,"total" => "-$total");
        $data[] = $deposit;
       
        echo json_encode($data);   

    }

    if($request_data->action=="confirmInf"){
        
        $methodID = $request_data -> method;
        $amountPaid = $request_data -> amountPaid;
        $allRoom = $request_data -> allRoom;
        $i = 0;
        
        for($i = 0 ; $i < count($amountPaid) ; $i++)
        {
            $roomID = intval($allRoom[$i]) ;
            $amount = floatval($amountPaid[$i]) ;
            $type = 2;
            
            // Get Booking Detail
            $sql = "SELECT bookingdetailID
                FROM bookingdetail
                WHERE status = 'I' AND
                    roomID = $roomID";

            $query = $connect->query($sql);
            while($row = $query -> fetch(PDO::FETCH_ASSOC)){
                $bookingDetailID = intval($row["bookingdetailID"]) ;
            }
            
            // Insert 
            $sql = "INSERT INTO payment (bookingDetailID,methodID,amountPaid,type,datePaid)
                    VALUES($bookingDetailID,$methodID,$amount,$type, CURDATE())
                    ";

            $query = $connect->query($sql);
                    
            // Update Status Check Out
            $sql = "UPDATE bookingdetail
                    SET status = 'O'
                    WHERE roomID = $roomID AND
                        bookingDetailID = $bookingDetailID AND
                        status = 'I'";
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

    if($request_data -> action == "getChargeRate"){
        
        $sql = "SELECT chargeRate
                FROM payment_method
                WHERE methodID = 3";

        $query = $connect->query($sql);
        
        while($row = $query -> fetch(PDO::FETCH_ASSOC)){
            $data = $row["chargeRate"];
        }   
        
        echo json_encode($data); 
    }

?>