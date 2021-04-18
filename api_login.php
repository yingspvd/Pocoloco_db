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
        //$row = $query->fetch(PDO::FETCH_BOTH);
        $output = array("message" => "Login Complete","login" => 1,"employeeID" => $employeeID);
        echo json_encode($output);
    }
    else
    {
        $output = array("message" => "No");
         echo json_encode($output);
    }
     
}
?>