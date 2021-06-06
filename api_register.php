<?php 
require_once 'connect.php';

$request_data=json_decode(file_get_contents("php://input"));
$data = array();
$out = array('error' => false, 'email'=> false, 'password' => false);

if($request_data->action == "register")
{
    function check_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }

    $email = check_input($request_data -> email);
	$password = check_input($request_data -> password);
    $password = md5($password);  
    
    if($email==''){
		$out['email'] = true;
		$out['message'] = "Email is required";
    }

    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $out['email'] = true;
        $out['message'] = "Invalid Email Format";
    }

    elseif($password==''){
        $out['password'] = true;
        $out['message'] = "Password is required";
    }

    else{
        $sql="SELECT * FROM newuser WHERE email='$email'";
		$query=$connect->query($sql);

		if($query->rowCount() > 0){
			$out['email'] = true;
			$out['message'] = "Email already exist";
		}

		else{
            //$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			$sql = "INSERT INTO newuser (email, password) VALUES ('$email', '$password')";
			$query = $connect->query($sql);

			if($query){
				$out['message'] = "User Added Successfully";
               // header("Location:loginNew.php");
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