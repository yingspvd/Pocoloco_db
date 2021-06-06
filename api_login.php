<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));
$data = array();

if($request_data->action == "login")
{
    // from user
    $employeeID = $request_data->employeeID;
    $password = md5($request_data->password);

    // query
    $sql = "SELECT * FROM employee WHERE employeeID = '$employeeID' AND password = '$password'";
    $query=$connect->query($sql);

    if($query->rowCount() == 1)
    {
        $sql = "SELECT r.roleName,d.departmentName,gender
            FROM employee e,role r,department d
            WHERE e.roleID = r.roleID AND
                e.department = d.departmentID AND 
                e.workStatus = 'E' AND
                e.employeeID = $employeeID";
                
        $query=$connect->query($sql);
        while($row = $query -> fetch(PDO::FETCH_ASSOC)){
            $roleName = $row["roleName"];
            $departmentName = $row["departmentName"];
            $gender = $row["gender"];
        }
        
        $output = array("login" => 1,"employeeID" => $employeeID,"roleName" => $roleName,"departmentName" => $departmentName,"gender"=>$gender);
        echo json_encode($output);
    }
    else
    {
        $output = array("message" => "No");
         echo json_encode($output);
    }
     
}
?>