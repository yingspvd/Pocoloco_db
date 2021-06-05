<?php
require_once 'connect.php';

$request_data = json_decode(file_get_contents("php://input"));

if($request_data->action == 'getAll') {
    $year = $request_data -> year;
    
    $sql = "SELECT * FROM payment_view
            WHERE datePaid LIKE '$year%'
            ORDER BY datePaid DESC";
            
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
    $direction = $request_data->direction;
    $year = $request_data->year;


    if($filter == "name" && $direction == "up"){
        $sql = "SELECT *
        From payment_view
        WHERE (guestFirstName LIKE '$search%' OR guestLastName LIKE '$search%') AND datePaid LIKE '$year%'
        ORDER BY $sort DESC";
    }
    else if($filter == "name" && $direction == "down"){
        $sql = "SELECT *
        From payment_view
        WHERE (guestFirstName LIKE '$search%' OR guestLastName LIKE '$search%') AND datePaid LIKE '$year%'
        ORDER BY $sort";
    }
    else if($direction == "up"){
        $sql = "SELECT *
        From payment_view
        WHERE $filter LIKE '$search%' AND datePaid LIKE '$year%'
        ORDER BY $sort DESC";
    }
    else if($direction == "down"){
        $sql = "SELECT *
        From payment_view
        WHERE $filter LIKE '$search%' AND datePaid LIKE '$year%'
        ORDER BY $sort";
    }
    else{
        $sql = "SELECT *
        From payment_view
        WHERE datePaid LIKE '$year%'
        ORDER BY datePaid DESC";
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