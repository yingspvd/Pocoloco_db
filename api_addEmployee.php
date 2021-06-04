<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));
$data = array();

// Check Error
$out = array('error' => false, 
            'department' => false,
            'roleID' => false,
            'startDate' => false,
            'shift' => false,
            'firstName' => false,
            'lastName' => false,
            'identification' => false,
            'DOB' => false,
            'gender' => false,
            'phone' => false,
            'email'=> false, 
            'password' => false,
            'cf_pass' => false);

if($request_data-> action == "getRole")
{
    // query
    $department = $request_data -> department;
    $role = $request_data -> role;

    if($role == "Admin" || $role == "Owner"){
      $out = 1;
      $sql = "SELECT roleName,roleID 
          FROM role r ,department d
          WHERE r.departmentID = d.departmentID AND
          d.departmentName = '$department'";
    }
    else{ $out = 2;
      $sql = "SELECT roleName,roleID 
            FROM role r ,department d
            WHERE r.departmentID = d.departmentID AND
            r.roleName != 'Manager' AND
            d.departmentName = '$department'
            ";
    }

    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }

    echo json_encode($data);

}

if($request_data->action == "addEmployee")
{
    function check_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }

    function replace_specialChar($phone) {
        //remove white space, dots, hyphens and brackets
        $phone = str_replace([' ', '.', '-', '(', ')'], '', $phone); 
        return $phone;
     }
         
    // From User
    $department = $request_data -> department;
    $roleID = $request_data -> roleID;
    $startDate = $request_data -> startDate;
    $shift = $request_data -> shift;
    $firstName = check_input($request_data -> firstName);
    $lastName = check_input($request_data -> lastName);
    $identification = replace_specialChar($request_data -> identification);
    $DOB = $request_data -> DOB;
    $gender = $request_data -> gender;
    $phone = replace_specialChar($request_data -> phone);
    $email = check_input($request_data -> email);
    $password = check_input($request_data -> password);
    $cf_pass = check_input($request_data -> cf_pass);
    
   
    $sql="SELECT * FROM employee WHERE email='$email'";
    $query=$connect->query($sql);

    // Check mail in DB
    if($query->rowCount() > 0){
      $out['email'] = true;
      $out['message'] = "Email already exist";
    }

    // Add in DB
    else{
        //Set value
        $roleID = intval($roleID);
        $shift = intval($shift);
        $password = md5($password);  
        $firstName = ucfirst($firstName);
        $lastName = ucfirst($lastName);
        
        $sql = "SELECT departmentID
                FROM department 
                WHERE departmentName ='$department'";
        $query = $connect->query($sql);
        while($row = $query -> fetch(PDO::FETCH_ASSOC)){
          $departmentID = $row["departmentID"];
        }

        //Query employeeID
        $sql_emID = "SELECT MAX(employeeID) AS employeeID FROM employee WHERE roleID = $roleID";
        $query = $connect->query($sql_emID);
        while($row = $query -> fetch(PDO::FETCH_ASSOC)){
          $data[] = $row;
        }

        //Set EmployeeID
        if($data[0]["employeeID"] == 0){
          $employeeID = ($roleID * 10000) + 1;
        }
        else{
          $employeeID = $data[0]["employeeID"] + 1;
        }

    $sql = "INSERT INTO employee 
          (employeeID ,department,roleID, startDate,shift, em_firstname,em_lastname,identification,DOB,gender,phone,email,password,workStatus) 
          VALUES ('$employeeID' ,'$departmentID','$roleID', '$startDate','$shift', '$firstName','$lastName','$identification','$DOB','$gender','$phone','$email', '$password','E')";
    $query = $connect->query($sql);

    if($query){
      $data['success'] = true;
      $data['message'] = "User Added Successfully";
    }
    else{
      $data['false'] = true;
      $data['message'] = "Could not add User";
    }
  }
	

    echo json_encode($data);
}

if($request_data -> action == "checkEmail"){
  $email = $request_data -> email;
  
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $error = true;
  }
  else{
    $error = false;
    
  }
  echo json_encode($error);
  
}

if($request_data -> action == "checkDOB"){
  $DOB = $request_data -> DOB;
  $year = intval($request_data -> year);
  
  $sql = "SELECT $year - EXTRACT(YEAR FROM '$DOB') AS diff";
  $query = $connect->query($sql);
  while($row = $query -> fetch(PDO::FETCH_ASSOC)){
    $diff = $row["diff"];
  }

  if($diff > 20){
    $check = true;
  }
  else{
    $check = false;
  }
  echo json_encode($check);
  
}

?>