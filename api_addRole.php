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

      if($departmentID=='') {
        $out['departmentID'] = true;
        $out['message'] = "DepartmentID is required";
      }

      else if ($roleName=='') {
        $out['roleName'] = true;
        $out['message'] = "Role Name is required";
      }

      else if($salary=='') {
        $out['salary'] = true;
        $out['message'] = "Salary Name is required";
      }

      else if ((is_numeric($salary) == false)) {
        $out['salary'] = true;
        $out['message'] = "Salary is not correct";  
      }

      else if($bonusRate =='') {
        $out['bonusRate'] = true;
        $out['message'] = "Bonus Rate is required";
      }

      else {
      $departmentID = intval($departmentID);
      $roleName = ucfirst($roleName);
      $salary = intval($salary);
      $bonusRate = floatval($bonusRate);  
            
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
          $out['message'] = "User Added Successfully";
        }
        else{
          $out['error'] = true;
          $out['message'] = "Could not add User";
        }
      }
    echo json_encode($out);
     
}
?>