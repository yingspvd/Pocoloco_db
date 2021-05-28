<?php
require_once 'connect.php';

$request_data = json_decode(file_get_contents("php://input"));

if($request_data->action == 'getAll') {
    $sql = "SELECT * FROM bookingdetail_view";
    $query = $connect->prepare($sql);
    $query->execute();
    while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data);
}

if($request_data->action == 'searchBookingDetail')
{
    $search = $request_data->search;
    $filter = $request_data->filter;
    $sort = $request_data->sort;

    if($sort == "all" && $filter == "all")
    {
        $sql = "SELECT * FROM bookingdetail_view 
                WHERE   bookingDetailID LIKE '$search%'OR
                        guestFirstName LIKE '$search%'OR
                        guestLastName LIKE '$search%'OR
                        checkIn LIKE '$search%'OR
                        checkOut LIKE '$search%'OR
                        status LIKE '$search%'
                ORDER BY bookingDetailID, guestFirstName, guestLastName, checkIn, checkOut, status
                ";
    }
    elseif($sort == "all" && $filter != "all"){
        $sql = "SELECT * FROM bookingdetail_view 
        WHERE $filter LIKE '$search%'
        ORDER BY bookingDetailID, guestFirstName, guestLastName, checkIn, checkOut, status
        "; 
    }
    elseif($sort != "all" && $filter == "all"){
        $sql = "SELECT * FROM bookingdetail_view 
                WHERE  bookingDetailID LIKE '$search%'OR
                        guestFirstName LIKE '$search%'OR
                        guestLastName LIKE '$search%'OR
                        checkIn LIKE '$search%'OR
                        checkOut LIKE '$search%'OR
                        status LIKE '$search%'
                ORDER BY $sort
                ";
    }
    
    else{
        $sql = "SELECT * FROM bookingdetail_view 
                WHERE $filter LIKE '$search%'
                ORDER BY $sort
                ";
    }
    
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

if($request_data->action == 'updateData')
{
    $bookingDetailID = $request_data->bookingDetailID;
    $guestFirstName = $request_data->guestFirstName;
    $guestLastName = $request_data->guestLastName;
    $checkIn = $request_data->checkIn;
    $checkOut = $request_data->checkOut;
    $status = $request_data->status;

    if($status == "Reserve") {
        $status = "R";
    }
    elseif($status == "Check IN") {
        $status = "I";
    }
    elseif($status == "Cancel") {
        $status = "C";
    }
    
    $sql = "UPDATE bookingDetail 
                SET guestFirstName = '$guestFirstName', 
                guestLastName = '$guestLastName', 
                checkIn = '$checkIn' ,
                checkOut = '$checkOut',
                status = '$status'
                WHERE bookingDetailID = '$bookingDetailID'
                ";

    $query = $connect->query($sql);

    if($query){
        $out['message'] = "Updated Successfully";
        $out['success'] = true;
        }
        else{
        $out['message'] = "Could not update ";
        }

    echo json_encode($out); 
}

if($request_data->action == 'deleteBookingDetail')
{
    $bookingDetailID = $request_data->bookingDetailID;
    $sql = "DELETE FROM bookingDetail 
            WHERE bookingDetailID = '$bookingDetailID'
            ";

    $query = $connect->query($sql);

    if($query){
        $out['message'] = "Delete Successfully";
        $out['success'] = true;
        }
        else{
        $out['message'] = "Could not delete ";
        }

    echo json_encode($out); 
}
?>