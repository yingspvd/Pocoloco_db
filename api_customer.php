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