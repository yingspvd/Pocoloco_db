<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == "getAllCustomer"){
    
    $sql = "SELECT c.*, n.numberVisit
            FROM customer c LEFT JOIN
            numberVisit n ON c.customerID = n.customerID
            ORDER BY n.numberVisit DESC, customerID";
                    
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

    ///////// แก้ให้เป็น expense ///////////////
    if($sort == "rank"){
        $sort = "customerID";
    }
    if($filter == "rank"){
        $filter = "customerID";
    }
    
    if($sort == "all"  && $filter == "all" ){
        $sql = "SELECT c.*, n.numberVisit
                FROM customer c LEFT JOIN
                numberVisit n ON c.customerID = n.customerID
                WHERE 
                    (c.customerID LIKE '%$search%' OR c.firstName LIKE '$search%' OR
                    c.lastName LIKE '$search%' )
                    ORDER BY n.numberVisit DESC,customerID";
    }
    
    elseif($sort != "all" && $sort != "numberVisit"  && $filter == "all" ){
        $sql = "SELECT c.*, n.numberVisit
            FROM customer c LEFT JOIN
            numberVisit n ON c.customerID = n.customerID
            WHERE 
            	(c.customerID LIKE '%$search%' OR c.firstName LIKE '$search%' OR
                c.lastName LIKE '$search%' )
            ORDER BY c.$sort";
    }

    elseif($sort == "numberVisit"  && $filter == "all" ){
        $sql = "SELECT c.*, n.numberVisit
            FROM customer c LEFT JOIN
            numberVisit n ON c.customerID = n.customerID
            WHERE 
            	(c.customerID LIKE '%$search%' OR c.firstName LIKE '$search%' OR
                c.lastName LIKE '$search%' )
                ORDER BY n.numberVisit DESC,customerID";
    }
    
    elseif($sort == "numberVisit"  && $filter != "all" && ($filter != 'customerID')){
        $sql = "SELECT c.*, n.numberVisit
                FROM customer c LEFT JOIN
                numberVisit n ON c.customerID = n.customerID
                WHERE (c.$filter LIKE '$search%' )
                ORDER BY n.numberVisit DESC,customerID";
    }

    elseif($sort == "numberVisit"  && $filter != "all" && ($filter == 'customerID')){
        $sql = "SELECT c.*, n.numberVisit
                FROM customer c LEFT JOIN
                numberVisit n ON c.customerID = n.customerID
                WHERE (c.$filter LIKE '%$search%' )
                ORDER BY n.numberVisit DESC,customerID";
    }
    
    elseif(($sort == "all") && ($filter == "numberVisit")){
        $sql = "SELECT c.*, n.numberVisit
                FROM customer c LEFT JOIN
                numberVisit n ON c.customerID = n.customerID
                WHERE 
                    (n.$filter LIKE '$search%' )
                ORDER BY n.numberVisit DESC,customerID";
    }
    
    elseif(($sort == "all") && ($filter != "all") && ($filter != 'customerID')){
        $sql = "SELECT c.*, n.numberVisit
                FROM customer c LEFT JOIN
                numberVisit n ON c.customerID = n.customerID
                WHERE   
                    (c.$filter LIKE '$search%' )
                ORDER BY n.numberVisit DESC,customerID";
    }

    elseif(($sort == "all") && ($filter == 'customerID')){
        $sql = "SELECT c.*, n.numberVisit
                FROM customer c LEFT JOIN
                numberVisit n ON c.customerID = n.customerID
                WHERE   
                    (c.$filter LIKE '%$search%' )
                ORDER BY n.numberVisit DESC,customerID";
    }

    elseif(($sort != "all") && ($filter != 'all') && ($filter == 'customerID')){
        $sql = "SELECT c.*, n.numberVisit
                FROM customer c LEFT JOIN
                numberVisit n ON c.customerID = n.customerID
                WHERE   
                    (c.$filter LIKE '%$search%' )
                ORDER BY c.$sort";
    }
    
    else{
        $sql = "SELECT c.*, n.numberVisit
                FROM customer c LEFT JOIN
                numberVisit n ON c.customerID = n.customerID
                WHERE 
                    (c.$filter LIKE '$search%' )
                ORDER BY c.$sort";
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