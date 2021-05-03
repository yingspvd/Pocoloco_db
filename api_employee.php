<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == "getEmployee"){
    $sql = "SELECT e.employeeID,e.em_firstname,e.em_lastname,d.departmentName,
                    r.roleName,r.salary, e.workStatus,e.shift,e.startDate,
                    e.identification,e.gender,e.DOB,e.email,e.phone
            FROM employee e,department d,role r
            WHERE e.department = d.departmentID AND
                    e.roleID = r.roleID";
                    
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    
    echo json_encode($data);   
} 

if($request_data -> action == "getDepartment"){
    $sql = "SELECT * FROM department";
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    echo json_encode($data);   
}

?>