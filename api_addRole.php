<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));
$data = array();

// Check Error
$out = array('error' => false, 
            'departmentID' => false,
            'roleName' => false,
            'salary' => false,
            'bonusRate' => false);

if($request_data-> action == "addRole")
{
    // From User
    $departmentID = $request_data -> departmentID;
    $roleName = $request_data -> roleName;
    $salary = $request_data -> salary;
    $bonusRate = $request_data -> bonusRate;

      
    $departmentID = intval($departmentID);
    $roleName = ucfirst($roleName);
    $salary = intval($salary);
    $bonusRate = intval($bonusRate);  
          
      //Query RoleID
      $sql_roleID = "SELECT MAX(roleID) AS roleID FROM role WHERE departmentID = $departmentID";
      $query = $connect->query($sql_roleID);
      while($row = $query -> fetch(PDO::FETCH_ASSOC)){
          $data[] = $row;
          }

        //Set EmployeeID
          if($data[0]["roleID"] == 0){
            $roleID = ($departmentID * 10) + 1;
          }
          else{
            $roleID = $data[0]["roleID"] + 1;
          }

      $sql = "INSERT INTO role
            (roleID ,departmentID,roleName, salary, bonusRate) 
            VALUES ('$roleID' ,'$departmentID','$roleName', '$salary','$bonusRate')";
      $query = $connect->query($sql);


      if($query){
        $out['message'] = "Added Successfully";
        $out['success'] = true;
    }
    else{
        $out['message'] = "Could not add this role";
        $out['success'] = false;
    }
      
    echo json_encode($out);
     
}
?>