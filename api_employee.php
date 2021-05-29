<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == "getEmployee"){
    
    $sql = "SELECT *
            FROM employee_view
            ORDER BY employeeID DESC";
                    
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    
    echo json_encode($data);   
} 
if($request_data -> action == "getEmployeeManager"){
    $department = $request_data -> department;

    $sql = "SELECT *
            FROM employee_view
            WHERE departmentName = '$department'
            ORDER BY roleName, employeeID DESC";
                    
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
    $department = $request_data -> department;
    
    $sql = "SELECT roleName 
            FROM role 
            WHERE departmentID IN 
                (SELECT departmentID 
                FROM department 
                WHERE departmentName = '$department')";
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row["roleName"];
    }

    if($query->rowCount() == 0){
        $data = "";
        
    }
    echo json_encode($data);

}

if($request_data -> action == "updateData"){
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
    else{

        $sql = "SELECT d.departmentID,r.roleID 
                FROM department d,role r 
                WHERE d.departmentID IN (SELECT departmentID 
                                        FROM department 
                                        WHERE departmentName = '$department') 
                        AND
                        r.roleID IN (SELECT roleID 
                                    FROM role 
                                    WHERE roleName = '$role')";

        $query = $connect->query($sql);
            
        while($row = $query -> fetch(PDO::FETCH_ASSOC)){
            $department = $row['departmentID'];
            $roleID = $row['roleID'];
        }

        if($workStatus == "Q"){
            $sql_insert = "UPDATE employee 
                        SET department = '$department', roleID = '$roleID', shift = '$shift', 
                        em_firstname = '$firstName', em_lastname = '$lastName' , phone = '$phone',
                        email = '$email', workStatus = '$workStatus',endDate = CURRENT_DATE()
                        WHERE employeeID = '$employeeID'";
        }
        else{
            $sql_insert = "UPDATE employee 
            SET department = '$department', roleID = '$roleID', shift = '$shift', 
            em_firstname = '$firstName', em_lastname = '$lastName' , phone = '$phone',
            email = '$email', workStatus = '$workStatus'
            WHERE employeeID = '$employeeID'";
        }

        $query = $connect->query($sql_insert);

        if($query){
            $out['message'] = "Updated Successfully";
            $out['success'] = true;
        }
        else{
            $out['message'] = "Could not update";
            $out['success'] = false;
        }
    }
    echo json_encode($out);
}

if($request_data -> action == "updateDataManager"){
    $employeeID = intval($request_data -> employeeID);
    $shift = intval($request_data -> shift) ;
    $workStatus = $request_data -> workStatus;

    $sql = "UPDATE employee
            SET workStatus = '$workStatus' , shift = '$shift'
            WHERE employeeID = $employeeID";

    $query = $connect->query($sql);

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

if($request_data -> action == "searchData"){
    $search = $request_data -> search;
    $filter = $request_data -> filter;
    $sort = $request_data -> sort;
    $direction = $request_data -> direction;

    if($direction == "up"){
        $sql = "SELECT *
                FROM employee_view
                WHERE $filter LIKE '$search%'
                ORDER BY $sort DESC";
    }
    else if($direction == "down"){
        $sql = "SELECT *
                FROM employee_view
                WHERE $filter LIKE '$search%'
                ORDER BY $sort";
    }
    else{
        $sql = "SELECT *
                FROM employeeview
                ORDER BY employeeID DESC";
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

if($request_data -> action == "searchDataManager"){
    $search = $request_data -> search;
    $filter = $request_data -> filter;
    $sort = $request_data -> sort;
    $direction = $request_data -> direction;
    $department = $request_data -> department;

    if($direction == "up"){
        $sql = "SELECT *
                FROM employee_view
                WHERE $filter LIKE '$search%' AND departmentName = '$department'
                ORDER BY $sort DESC";
    }
    else if($direction == "down"){
        $sql = "SELECT *
                FROM employee_view
                WHERE $filter LIKE '$search%' AND departmentName = '$department'
                ORDER BY $sort";
    }
    else{
        $sql = "SELECT *
                FROM employeeview
                WHERE departmentName = '$department'
                ORDER BY employeeID DESC";
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