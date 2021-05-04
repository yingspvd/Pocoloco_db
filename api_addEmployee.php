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
    $department = intval($department);
    
    $sql = "SELECT roleName,roleID FROM role WHERE departmentID = $department";
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }

    echo json_encode($data);

}

if($request_data -> action == "test")
{
  $out['message'] = true;
  echo json_encode($out);
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
    

    // Check Department
    if($department==''){
		$out['department'] = true;
		$out['message'] = "Department is required";
    }
    
    // Check roleID
    else if($roleID==''){
		$out['roleID'] = true;
		$out['message'] = "roleID is required";
    }

    // Check startDate
    else if($startDate==''){
		$out['startDate'] = true;
		$out['message'] = "Start Date is required";
    }

    // Check Shift
    else if($shift==''){
		$out['shift'] = true;
		$out['message'] = "Shift is required";
    }

    // Check FirstName
    else if($startDate==''){
		$out['firstName'] = true;
		$out['message'] = "FirstName is required";
    }

    // Check LastName
    else if($startDate==''){
		$out['lastName'] = true;
		$out['message'] = "LastName is required";
    }

    // Check Identification  ** เช็คเลข 13 ตัว **
    else if($identification==''){
		$out['lastName'] = true;
		$out['message'] = "Identification is required";
    }

    // Check identification ตัวเลข & 13 ตัว
    else if ((is_numeric($identification) == false) || (strlen($identification) != 13))
    {
        $out['identification'] = true;
        $out['message'] = "Identification is not correct";
    }

    // Check DOB
    else if($DOB==''){
		$out['DOB'] = true;
		$out['message'] = "Birth Date is required";
    }

    // Check Gender
    else if($gender==''){
		$out['gender'] = true;
		$out['message'] = "Gender is required";
    }

    // Check Phone 
    else if($phone==''){
		$out['phone'] = true;
		$out['message'] = "Phone Number is required";
    }

  
    // Check Phone ตัวเลข & 10 ตัว
    else if ((is_numeric($phone) == false) || (strlen($phone) != 10))
    {
        $out['phone'] = true;
        $out['message'] = "Phone Number is not correct";  
    }

    // Check Email
    else if($email==''){
		$out['email'] = true;
		$out['message'] = "Email is required";
    }

    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $out['email'] = true;
        $out['message'] = "Invalid Email Format";
    }

    // Check Password 
    elseif($password==''){
        $out['password'] = true;
        $out['message'] = "Password is required";
    }

    // Check Confirm Password
    elseif($cf_pass==''){
      $out['cf_pass'] = true;
      $out['message'] = "Confirm Password is required";
    }

    // Check password match
    else if($password != $cf_pass){
      $out['password'] = true;
      $out['cf_pass'] = true;
		  $out['message'] = "Password does not match";
    }

    // Check Password at least 8 characters
    else if(strlen($password) < 8){
      $out['password'] = true;
      $out['message'] = "Password must be at least 8 characters";
    }
    
    else{
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
            $department = intval($department);
            $roleID = intval($roleID);
            $shift = intval($shift);
            $password = md5($password);  
            $firstName = ucfirst($firstName);
            $lastName = ucfirst($lastName);
            
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
              VALUES ('$employeeID' ,'$department','$roleID', '$startDate','$shift', '$firstName','$lastName','$identification','$DOB','$gender','$phone','$email', '$password','E')";
        $query = $connect->query($sql);

        if($query){
          $out['message'] = "User Added Successfully";
        }
        else{
          $out['error'] = true;
          $out['message'] = "Could not add User";
        }
      }
	}

    echo json_encode($out);
}

?>