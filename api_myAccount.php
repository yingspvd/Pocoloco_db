<?php
require_once 'connect.php';

$request_data = json_decode(file_get_contents("php://input"));

if($request_data->action == 'getAll') {
    $employeeID = intval($request_data->employeeID) ;

    $sql = "SELECT * FROM account_view WHERE employeeID = $employeeID";
    $query = $connect->prepare($sql);
    $query->execute();
    while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data);
}

if($request_data->action == 'updateData')
{
    $employeeID = $request_data->employeeID;
    $em_firstname = $request_data->em_firstname;
    $em_lastname = $request_data->em_lastname;
    $phone = $request_data->phone;
    $email = $request_data->email;
    
    $sql = "UPDATE employee 
                SET em_firstname = '$em_firstname', 
                em_lastname = '$em_lastname', 
                phone = '$phone' ,
                email = '$email'
                WHERE employeeID = '$employeeID'
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
?>