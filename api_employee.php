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


if($request_data-> action == "getRole")
{
    // query
    $department = $request_data -> department;
    
    $sql = "SELECT roleName,roleID 
            FROM role 
            WHERE departmentID = 
                (SELECT departmentID 
                FROM department 
                WHERE departmentName = '$department')";
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }

    echo json_encode($data);

}

if($request_data -> action == "saveEdit"){
    $employeeID = intval($request_data -> employeeID);
    $department = $request_data -> department;
    $role = $request_data -> role;
    $shift = intval($request_data -> shift);
    $workStatus = $request_data -> workStatus;
    $firstName = $request_data -> firstName;
    $lastName = $request_data -> lastName;
    $phone = $request_data -> phone;
    $email = $request_data -> email;

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $out['message'] = "Invalid Email Format";
    }

    $sql = "SELECT d.departmentID,r.roleID 
            FROM department d,role r 
            WHERE d.departmentID = (SELECT departmentID 
                                    FROM department 
                                    WHERE departmentName = '$department') 
                    AND
                    r.roleID = (SELECT roleID 
                                FROM role 
                                WHERE roleName = '$role')";

    $query = $connect->query($sql);
        
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $department = $row['departmentID'];
        $roleID = $row['roleID'];
    }


    $sql_insert = "UPDATE employee 
                    SET department = '$department', roleID = '$roleID', shift = '$shift', 
                    em_firstname = '$firstName', em_lastname = '$lastName' , phone = '$phone',
                    email = '$email', workStatus = '$workStatus'
                    WHERE employeeID = '$employeeID'";
    
    $query = $connect->query($sql_insert);

    if($query){
        $out['message'] = "Updated Successfully";
        $out['success'] = true;
    }
    else{
        $out['message'] = "Could not update";
        $out['success'] = false;
    }
    
    echo json_encode($out);
}

?>