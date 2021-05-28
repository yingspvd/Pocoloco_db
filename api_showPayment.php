<?php
require_once 'connect.php';

$request_data = json_decode(file_get_contents("php://input"));

if($request_data->action == 'getAll') {
    $sql = "SELECT * FROM payment_view";
    $query = $connect->prepare($sql);
    $query->execute();
    while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data);
}

if($request_data->action == 'searchPayment')
{
    $search = $request_data->search;
    $filter = $request_data->filter;
    $sort = $request_data->sort;

    if($sort == "all" && $filter == "all")
    {
        $sql = "SELECT * FROM payment_view
                WHERE   paymentID LIKE '$search%'OR
                        methodID LIKE '$search%'OR
                        type LIKE 'deposit'OR
                        amountPaid LIKE '$search%'OR
                        datePaid LIKE '$search%' OR
                        bookingDetailID LIKE '$search%'OR
                        guestFirstName LIKE '$search%'OR
                        guestLastName LIKE '$search%'
                ORDER BY paymentID, methodID, type, amountPaid, datePaid, bookingDetailID, guestFirstName, guestLastName
                ";
    }
    elseif($sort == "all" && $filter != "all"){
        $sql = "SELECT * FROM payment_view 
                WHERE $filter LIKE '$search%' 
                ORDER BY paymentID, methodID, type, amountPaid, datePaid, bookingDetailID, guestFirstName, guestLastName
        ";
        
    }
    elseif($sort != "all" && $filter == "all"){
        $sql = "SELECT * FROM payment_view
                WHERE   paymentID LIKE '$search%'OR
                        methodID LIKE '$search%'OR
                        type LIKE 'deposit'OR
                        amountPaid LIKE '$search%'OR
                        datePaid LIKE '$search%' OR
                        bookingDetailID LIKE '$search%'OR
                        guestFirstName LIKE '$search%'OR
                        guestLastName LIKE '$search%'
                ORDER BY $sort
                ";
    }
    else{
        $sql = "SELECT * FROM payment_view
                WHERE $filter LIKE '$search%'
                ORDER BY $sort
                ";
    }
    $query = $connect->prepare($sql);
    $query->execute();    
    while($row = $query -> fetch(PDO::FETCH_BOTH)){
        $data[] = $row;
    }

    if($query->rowCount() == 0)
    {
        $data = "";
    }
   
    echo json_encode($data);

}

?>