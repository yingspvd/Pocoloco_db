<?php
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));
$data = array();


if($request_data->action=="getSearchData"){
    $search = $request_data->search;
    
    $query="SELECT r.roleID, r.roleName, r.salary, r.bonusRate, d.departmentName 
    FROM role r, department d
    WHERE r.roleID LIKE '%$search%' OR r.roleName LIKE '%$search%' OR r.salary LIKE '%$search%' OR r.bonusRate LIKE '%$search%' OR d.departmentName  LIKE '%$search%'
    GROUP BY r.roleID";
    $statement=$connect->prepare($query);
    $statement->execute();  //ไม่มี data ไม่ได้โยนข้อมูลไป
    // loop เก็บข้อมูลลงไปใน data 
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        $data[]=$row;
    }
    
    echo json_encode($data);   //table
}

if($request_data->action=="getAll"){
    $query="SELECT r.roleID, r.roleName, r.salary, r.bonusRate, d.departmentName 
    FROM role r, department d
    WHERE r.departmentID = d.departmentID
    GROUP BY r.roleID";
    $statement=$connect->prepare($query);
    $statement->execute();  //ไม่มี data ไม่ได้โยนข้อมูลไป
    // loop เก็บข้อมูลลงไปใน data 
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        $data[]=$row;
    }
    
    echo json_encode($data);   //table
}

if($request_data->action=="getEditUser"){
    $query="SELECT r.roleID, r.roleName, r.salary, r.bonusRate, d.departmentName 
            FROM role r, department d 
            WHERE roleID = $request_data->roleID 
                AND r.departmentID = d.departmentID";
    $statement=$connect->prepare($query);
    $statement->execute(); 
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        // เปลี่ยนจาก array เป็นobject
        $data['roleID']=$row['roleID'];
        $data['roleName']=$row['roleName'];
        $data['salary']=$row['salary'];
        $data['bonusRate']=$row['bonusRate'];
        $data['departmentID']=$row['departmentName'];
    }
    
    echo json_encode($data);   //table
}

if($request_data->action == "update"){
    //จัดเตรียมข้อมูล
    $data = array(":roleID" => $request_data->roleID,
                ":salary" => $request_data -> salary,
                ":bonusRate" => $request_data -> bonusRate,);
    $query = "UPDATE role SET  salary = :salary, bonusRate = :bonusRate WHERE roleID = :roleID";
    $statement = $connect -> prepare($query);
    $statement -> execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output);
}

if($request_data->action == "deleteUser"){
    $query = "DELETE FROM role WHERE roleID = $request_data->roleID";
    $statement = $connect -> prepare($query);
    $statement -> execute();
    $output = array("message" => "Delete Complete");
    echo json_encode($output);
}
?>