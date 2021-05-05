<?php
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));
$data = array();

if($request_data->action == 'getallService')
{
    $sql="SELECT * FROM service_view";
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

    if($filter == "all" && $sort == "all")
    {
        $sql = "SELECT * FROM service_view 
                WHERE   type LIKE '$search%'OR
                        name LIKE '$search%'OR
                        servicePrice LIKE '$search%'
                ORDER BY type,name,servicePrice 
                ";
    }
    elseif($filter == "all" && $sort != "all"){
        $sql = "SELECT * FROM service_view 
                WHERE  type LIKE '$search%'OR
                        name LIKE '$search%'OR
                        servicePrice LIKE '$search%'
                ORDER BY $sort
                ";
    }
    elseif($filter != "all" && $sort == "all"){
        $sql = "SELECT * FROM service_view 
                WHERE $filter LIKE '$search%'
                ORDER BY type,name,servicePrice 
                ";
    }
    else{
        $sql = "SELECT * FROM service_view  
                WHERE $filter LIKE '$search%'
                ORDER BY $sort
                ";
    }
    
    $query = $connect->query($sql);
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
  
    echo json_encode($data);

}
    
if($request_data->action == 'updateData')
{
    $serviceID = $request_data->serviceID;
    $type = $request_data->type;
    $name = $request_data->name;
    $servicePrice = $request_data->servicePrice;

    if($type == "Food"){
        $type = 1;
    }
    elseif($type == "Room Facilities"){
        $type = 2;
    }
    
    $sql = "UPDATE servicelist 
                SET type = '$type', 
                name = '$name' ,
                servicePrice = '$servicePrice'
                WHERE serviceID = '$serviceID'
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