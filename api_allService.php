<?php
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));
$data = array();

if($request_data->action == 'getAllService')
{
    $role = $request_data-> role;
    $department = $request_data-> department;
    
    if($role == "Owner" || $role == "Admin" || $department == "Receptionist" ){
        $sql="SELECT * FROM service_view";
    }
    else if($department == "Kitchen"){
        $sql="SELECT * FROM service_view WHERE type LIKE 'Food & Beverage'";
    }
    else if($department  == "Housekeeping"){
        $sql="SELECT * FROM service_view WHERE type LIKE 'Room Facilities'";
    }
    
    $query = $connect->query($sql);
        
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }

    echo json_encode($data);   
}

if($request_data->action == 'searchService')
{
    $search = $request_data->search;
    $filter = $request_data->filter;
    $sort = $request_data->sort;
    $direction = $request_data->direction;
    $role = $request_data->role;
    $type = $request_data->type;
    $department = $request_data-> department;
    
    if($role == "Owner" || $role == "Admin" || $department == "Receptionist" ){
        if($filter == "name" && $direction == "up"){
            $sql = "SELECT * 
                    FROM service_view 
                    WHERE   $filter LIKE '%$search%'
                    ORDER BY $sort DESC
                    ";
        }
        else if($filter == "name" && $direction == "down"){
            $sql = "SELECT * 
                    FROM service_view 
                    WHERE   $filter LIKE '%$search%'
                    ORDER BY $sort
                    ";
        }
        else if($direction == "up"){
            $sql = "SELECT * 
                    FROM service_view 
                    WHERE   $filter LIKE '$search%'
                    ORDER BY $sort DESC
                    ";
        }
        else if($direction == "down"){
            $sql = "SELECT * 
                    FROM service_view 
                    WHERE   $filter LIKE '$search%'
                    ORDER BY $sort
                    ";
        }
        else{
            $sql = "SELECT * 
                    FROM service_view  
                    ORDER BY name
                    ";
        }
        
    }
    else{
        if($filter == "name" && $direction == "up"){
            $sql = "SELECT * 
                    FROM service_view 
                    WHERE   $filter LIKE '%$search%' AND 
                    type LIKE '$type%'
                    ORDER BY $sort DESC
                    ";
        }
        else if($filter == "name" && $direction == "down"){
            $sql = "SELECT * 
                    FROM service_view 
                    WHERE   $filter LIKE '%$search%' AND
                    type LIKE '$type%'
                    ORDER BY $sort
                    ";
        }
        else if($direction == "up"){
            $sql = "SELECT * 
                    FROM service_view 
                    WHERE   $filter LIKE '$search%' AND 
                    type LIKE '$type%'
                    ORDER BY $sort DESC
                    ";
        }
        else if($direction == "down"){
            $sql = "SELECT * 
                    FROM service_view 
                    WHERE   $filter LIKE '$search%' AND
                    type LIKE '$type%'
                    ORDER BY $sort
                    ";
        }
        else{
            $sql = "SELECT * 
                    FROM service_view  
                    WHERE type LIKE '$type%'
                    ORDER BY name
                    ";
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
    
if($request_data->action == 'updateData')
{
    $serviceID = intval($request_data->serviceID) ;
    $type = intval($request_data->type);
    $name = $request_data->name;
    $servicePrice = floatval($request_data->servicePrice) ;

    $sql = "UPDATE servicelist 
                SET type = $type, 
                name = '$name' ,
                servicePrice = $servicePrice
                WHERE serviceID = $serviceID
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

if($request_data->action == 'deleteService')
{
    $serviceID = $request_data -> serviceID;
    $sql = "DELETE FROM servicelist 
            WHERE serviceID = '$serviceID'
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