<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == "getServiceActivity"){
    $role = $request_data-> role;
    $department = $request_data-> department;
    $year = $request_data-> year;
    
    if($role == "Owner" || $role == "Admin" || $department == "Receptionist" ){
        $sql = "SELECT *
        FROM serviceactivity_view
        WHERE date LIKE '$year%'
        ORDER BY date DESC
       ";
    }
    else if($department == "Kitchen"){
        $sql="SELECT * FROM serviceact_chef_view
            WHERE date LIKE '$year%'
            ORDER BY date DESC";
    }
    else if($department == "Housekeeping"){
        $sql="SELECT * FROM serviceact_maid_view
            WHERE date LIKE '$year%'
            ORDER BY date DESC";
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

if($request_data -> action == "getServiceData"){
    
    $roomID = intval($request_data -> roomID);
    $date = $request_data -> date;
    $role = $request_data -> role;
    $department = $request_data -> department;

    if($role == "Owner" || $role == "Admin" || $department == "Receptionist"){
        $sql = "SELECT *
            FROM servicelist_view
            WHERE DATETIME LIKE '$date%' AND roomID = $roomID 
            ORDER BY DATETIME";
    }
    else if($department == "Kitchen"){
        $sql = "SELECT *
            FROM servicelist_view
            WHERE DATETIME LIKE '$date%' AND TYPE = 2 AND roomID = $roomID 
            ORDER BY DATETIME";
    }
    else if($department == "Housekeeping"){
        $sql = "SELECT *
            FROM servicelist_view
            WHERE DATETIME LIKE '$date%' AND TYPE = 1 AND roomID = $roomID 
            ORDER BY DATETIME";
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

if($request_data->action == 'searchActivity')
{
    $search = $request_data->search;
    $filter = $request_data->filter;
    $direction = $request_data->direction;
    $sort = $request_data->sort;
    $role = $request_data->role;
    $department = $request_data->department;
    $year = $request_data->year;
    
    if($department == "Kitchen"){
        if($direction == "up"){
            $sql = "SELECT *
                FROM serviceact_chef_view
                WHERE $filter LIKE '$search%' AND date LIKE '$year%'  
                ORDER BY $sort DESC";
        }
        else if($direction == "down"){
            $sql = "SELECT *
                FROM serviceact_chef_view
                WHERE $filter LIKE '$search%' AND date LIKE '$year%'  
                ORDER BY $sort";
        }
        else{
            $sql = "SELECT *
                FROM serviceact_chef_view
                WHERE date LIKE '$year%' 
                ORDER BY date DESC";
        }
    }
    else if($department == "Housekeeping"){
        if($direction == "up"){
            $sql = "SELECT *
                FROM serviceact_maid_view
                WHERE $filter LIKE '$search%' AND date LIKE '$year%'  
                ORDER BY $sort DESC";
        }
        else if($direction == "down"){
            $sql = "SELECT *
                FROM serviceact_maid_view
                WHERE $filter LIKE '$search%' AND date LIKE '$year%'  
                ORDER BY $sort";
        }
        else{
            $sql = "SELECT *
                FROM serviceact_maid_view
                WHERE date LIKE '$year%' 
                ORDER BY date DESC";
        }
    }
    else{
        if($direction == "up"){
            $sql = "SELECT *
                FROM serviceactivity_view
                WHERE $filter LIKE '$search%' AND date LIKE '$year%' 
                ORDER BY $sort DESC";
        }
        else if($direction == "down"){
            $sql = "SELECT *
                FROM serviceactivity_view
                WHERE $filter LIKE '$search%' AND date LIKE '$year%' 
                ORDER BY $sort";
        }
        else{
            $sql = "SELECT *
                FROM serviceactivity_view
                WHERE date LIKE '$year%' 
                ORDER BY date DESC";
        }
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
?>