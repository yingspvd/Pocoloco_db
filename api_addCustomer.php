<?php
require_once 'connect.php';

$request_data = json_decode(file_get_contents("php://input"));
$data = array();

$out = array('firstName' => false,
            'lastName' => false,
            'DOB' => false,
            'gender' => false,
            'phone' => false,
            'email'=> false, 
            'address' => false);

if($request_data -> action == "getCustomerID"){
    $sql = "SELECT MAX(customerID) + 1 AS customerID FROM customer";
    $query = $connect->query($sql);
  
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $customerID = $row["customerID"];
    }
    
    echo json_encode($customerID);
}

        
if ($request_data->action == "insert") {
    function check_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function replace_specialChar($phone)
    {
        //remove white space, dots, hyphens and brackets
        $phone = str_replace([' ', '.', '-', '(', ')'], '', $phone);
        return $phone;
    }

    $firstName = check_input($request_data->firstName);
    $lastName = check_input($request_data->lastName);
    $DOB = $request_data->DOB;
    $gender = $request_data->gender;
    $phone = replace_specialChar($request_data->phone);
    $email = check_input($request_data->email);
    $address = $request_data->address;



    
    //Check Phone
    if ((is_numeric($phone) == false) || (strlen($phone) != 10)) {
        $out['phone'] = true;
        $out['message'] = "Phone Number is not correct";
        echo json_encode($out);
    }

    //Check email
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $out['email'] = true;
        $out['message'] = "Invalid Email Format";
        echo json_encode($out);
    } 
    else {
        $sql = "INSERT INTO customer 
        (firstname,lastname,DOB,gender,phone,email,address) 
        VALUES ('$firstName','$lastName','$DOB','$gender','$phone','$email','$address')";
        $query = $connect->query($sql);

        if($query){
            $out['message'] = "Added Successfully";
            $out['success'] = true;
          }
          else{
            $out['error'] = true;
            $out['message'] = "Could not add ";
          }
          
    }
    echo json_encode($out);
}
?>