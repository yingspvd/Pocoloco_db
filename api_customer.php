<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == "getCustomer"){
    $sql = "SELECT c.customerID, concat(c.firstName,' ', c.lastName) AS customerName, 
            c.DOB, c.gender, c.phone, c.email, c.address,
            COUNT(b.bookingID) AS numberStay
            FROM customer c, booking b
            WHERE c.customerID = b.customerID
            GROUP BY b.customerID                     
            ORDER BY numberStay DESC,b.customerID";
                    
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    
    echo json_encode($data);   
} 

if($request_data->action=="searchData"){
    $search = $request_data->search;
    
    $sql="SELECT c.customerID, concat(c.firstName,' ', c.lastName) AS customerName, 
            c.DOB, c.gender, c.phone, c.email, c.address,
            COUNT(b.bookingID) AS numberStay
            FROM customer c, booking b
            WHERE c.customerID = b.customerID AND 
            	(c.customerID LIKE '%$search%' OR c.firstName LIKE '$search%' OR
                c.lastName LIKE '$search%' )
            GROUP BY b.customerID                     
            ORDER BY numberStay DESC,b.customerID";
            
    $query = $connect->query($sql);
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
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

if($request_data -> action == "deleteData"){
    $sql = "DELETE FROM customer WHERE customerID = $request_data->customerID";
    $query = $connect->query($sql);

    if($query){
        $out['message'] = "Deleted Successfully";
        $out['success'] = true;
        }
    else{
        $out['message'] = "Could not delete ";
    }

    echo json_encode($out); 
}