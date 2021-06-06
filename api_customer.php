<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == "getAllCustomer"){
    
    $sql = "SELECT *
            FROM customer_view
            ORDER BY rank
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

if($request_data->action=="searchData"){
    
    $search = $request_data->search;
    $sort = $request_data -> sort;
    $filter = $request_data -> filter;
    $direction = $request_data -> direction;
    
    if($filter == "name" && $direction == "up"){
        $sql = "SELECT * 
                FROM customer_view
                WHERE (firstName LIKE '$search%' OR lastName LIKE '$search%')
                ORDER BY $sort DESC";
    }
    else if($filter == "name" && $direction == "down"){
        $sql = "SELECT * 
                FROM customer_view
                WHERE (firstName LIKE '$search%' OR lastName LIKE '$search%')
                ORDER BY $sort";
    }
    else if($direction == "up"){
        $sql = "SELECT * 
                FROM customer_view
                WHERE $filter LIKE '$search%'
                ORDER BY $sort DESC";
    }
    else if($direction == "down"){
        $sql = "SELECT * 
                FROM customer_view
                WHERE $filter LIKE '$search%'
                ORDER BY $sort";
    }
    else{
        $sql = "SELECT *
            FROM customer_view
            ORDER BY rank
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

if($request_data -> action == "editData"){
    
    $customerID = $request_data -> customerID;
    
    $sql = "SELECT *
            FROM customer
            WHERE customerID = '$customerID' ";
    
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    echo json_encode($data);  
}

if($request_data -> action == "updateData"){
    $customerID = $request_data -> customerID;
    $firstName = $request_data -> firstName;
    $lastName = $request_data -> lastName;
    $DOB = $request_data -> DOB;
    $phone = $request_data -> phone;
    $email = $request_data -> email;
    $address = $request_data -> address;

    $sql = "UPDATE customer 
            SET firstName = '$firstName', lastName = '$lastName', DOB = '$DOB',
                phone = '$phone', email = '$email' , address = '$address'
            WHERE customerID = '$customerID'";
    $query = $connect->query($sql);

    if($query){
        $out['message'] = "Updated Successfully";
        $out['success'] = true;
        }
        else{
        $out['message'] = "Could not add ";
        }

    echo json_encode($out); 
}
?>