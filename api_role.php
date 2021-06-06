<?php
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));
$data = array();

if($request_data->action=="getSearchData"){
    
    $search = $request_data->search;
    $sort = $request_data -> sort;
    $filter = $request_data -> filter;
    $direction = $request_data -> direction;
    
    if($direction == "up"){
        $sql = "SELECT *
                FROM role_view
                WHERE $filter LIKE '$search%'
                ORDER BY $sort DESC";
    }
    else if($direction == "down"){
        $sql = "SELECT *
                FROM role_view
                WHERE $filter LIKE '$search%'
                ORDER BY $sort";
    }
    else{
        $sql = "SELECT *
                FROM role_view
                ORDER BY roleID
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

if($request_data->action=="getAll"){
    
    $query="SELECT *
            FROM role_view
            ORDER BY roleID";
    
    $statement=$connect->prepare($query);
    $statement->execute();  
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        $data[]=$row;
    }
    
    echo json_encode($data);  
    
}

if($request_data->action=="getEditUser"){
    
    $query="SELECT r.roleID, r.roleName, r.salary, r.bonusRate, d.departmentName 
            FROM role r, department d 
            WHERE roleID = $request_data->roleID 
                AND r.departmentID = d.departmentID";
                
    $statement=$connect->prepare($query);
    $statement->execute(); 
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        $data['roleID']=$row['roleID'];
        $data['roleName']=$row['roleName'];
        $data['salary']=$row['salary'];
        $data['bonusRate']=$row['bonusRate'];
        $data['departmentID']=$row['departmentName'];
    }
    
    echo json_encode($data);   
}

if($request_data->action == "update"){
    $data = array(":roleID" => $request_data->roleID,
                ":salary" => $request_data -> salary,
                ":bonusRate" => $request_data -> bonusRate,);
    $query = "UPDATE role SET  salary = :salary, bonusRate = :bonusRate WHERE roleID = :roleID";
    $statement = $connect -> prepare($query);
    $statement -> execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output);
}

if($request_data->action == "deleteData"){
    $roleID = intval($request_data -> roleID);
    $sql = "DELETE FROM role WHERE roleID = $roleID";
    $query = $connect->query($sql);

    if($query){
        $out['message'] = "Delete Complete";
    }
    else{
        $out['message'] = "Cannot Delete this role";
    }
    echo json_encode($out);
}
?>