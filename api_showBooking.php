<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));
$data = array();

if($request_data->action == 'getAll') {
    $sql = "SELECT * FROM booking_view";
    $query = $connect->prepare($sql);
    $query->execute();
    while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data);
}

if($request_data->action == 'searchBooking')
{
    $search = $request_data->search;
    $filter = $request_data->filter;
    $sort = $request_data->sort;

    if($filter == "all" && $sort == "all")
    {
        $out = 1;
        $sql = "SELECT * FROM booking_view 
                WHERE   bookingDetailID LIKE '$search%'OR
                        guestFirstName LIKE '$search%'OR
                        guestLastName LIKE '$search%'OR
                        checkIn LIKE '$search%'OR
                        checkOut LIKE '$search%'OR
                        status LIKE '$search%'
                ORDER BY bookingDetailID, guestFirstName, guestLastName, checkIn, checkOut, status
                ";
    }
    elseif($filter == "all" && $sort != "all"){
        $out = 2;

        $sql = "SELECT * FROM booking_view 
                WHERE  bookingDetailID LIKE '$search%'OR
                        guestFirstName LIKE '$search%'OR
                        guestLastName LIKE '$search%'OR
                        checkIn LIKE '$search%'OR
                        checkOut LIKE '$search%'OR
                        status LIKE '$search%'
                ORDER BY $sort
                ";
    }
    elseif($filter == "status" && $sort == "all"){
        $out = 5;
        $sql = "SELECT * FROM booking_view 
                WHERE status LIKE '$search%'
                ";
        
    }
    elseif($filter != "all" && $sort == "all"){
        $out = 3;
        $sql = "SELECT * FROM booking_view 
                WHERE $filter LIKE '$search%'
                ";
    }
    
    else{
        $out = 4;
        $sql = "SELECT * FROM booking_view 
                WHERE $filter LIKE '$search%'
                ORDER BY $sort
                ";
    }
    
    $query = $connect->query($sql);
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    $num = $query->rowCount();
  
    echo json_encode($data);


}
?>