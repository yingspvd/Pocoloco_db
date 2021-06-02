<?php
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));
$data = array();

if($request_data->action=="getAll"){
    $query="SELECT *
            FROM booking_view
            ORDER BY bookingID DESC";
    $statement=$connect->prepare($query);
    $statement->execute();
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        $data[]=$row;
    }

    if($statement->rowCount() == 0){
        $data = "";
        
    }
   
    echo json_encode($data);   
}

if($request_data->action=="SearchData"){
    $search = $request_data->search;
    $sort = $request_data -> sort;
    $filter = $request_data -> filter;
    $direction = $request_data -> direction;

    if($direction == "up"){
        $sql = "SELECT *
        FROM booking_view
        WHERE $filter LIKE '$search%'
        ORDER BY $sort DESC";
    }
    else if($direction == "down"){
        $sql = "SELECT *
        FROM booking_view
        WHERE $filter LIKE '$search%'
        ORDER BY $sort";
    }
    else{
        $sql = "SELECT *
        FROM booking_view
        ORDER BY bookingID DESC";
    }
   
    $query = $connect->query($sql);
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }

    if($query->rowCount() == 0){
        $data = "";
        
    }
  

    echo json_encode($data);   
}

if($request_data->action=="getBookingDetail"){
    $bookingID = intval($request_data->bookingID);
    
    $query="SELECT *
            FROM bookingdetail_view 
            WHERE bookingID = $bookingID"
            ;
    $statement=$connect->prepare($query);
    $statement->execute(); 
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
       
        $data[]=$row;      
    }
    if($statement->rowCount() == 0){
        $data = "";
        
    }
    echo json_encode($data);   
}

if($request_data->action=="getEditDetail"){
    $query="SELECT bookingDetailID, guestFirstname, guestLastname, checkIn, checkOut, status
            FROM bookingDetail 
            WHERE bookingDetailID = $request_data->bookingDetailID"
            ;
    $statement=$connect->prepare($query);
    $statement->execute(); 
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        $data['bookingDetailID']=$row['bookingDetailID'];
        $data['guestFirstname']=$row['guestFirstname'];
        $data['guestLastname']=$row['guestLastname'];
        $data['checkIn']=$row['checkIn'];
        $data['checkOut']=$row['checkOut'];
        $data['statusRoom']=$row['status'];       
    }
    if($statement->rowCount() == 0){
        $data = "";
        
    }
    echo json_encode($data);  
}

if($request_data->action == "update"){
    $data = array(":bookingDetailID" => $request_data -> bookingDetailID,
                ":guestFirstname" => $request_data -> guestFirstname,
                ":guestLastname" => $request_data -> guestLastname,
                ":checkIn" => $request_data -> checkIn,
                ":checkOut" => $request_data -> checkOut,
                ":statusRoom" => $request_data -> statusRoom);

    $query = "UPDATE bookingdetail SET  guestFirstName = :guestFirstname, guestLastName = :guestLastname, 
                                        checkIn = :checkIn,  checkOut = :checkOut, status = :statusRoom
    WHERE bookingDetailID = :bookingDetailID";
    $statement = $connect -> prepare($query);
    $statement -> execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output);
                
}


?>