<?php
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));
$data = array();

// var_dump($request_data);
$out = array('roomNumber' => false,
            'detail' => false,
            'expense' => false,
            'expenseDate' => false);



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

    $roomNumber = $request_data->roomNumber;
    $detail = $request_data->detail;
    $expense = $request_data->expense;
    $expenseDate = $request_data->expenseDate;
    
    if (is_numeric($expense) == false){
        $out['message'] = "Expense is not correct";
        echo json_encode($out);
    }
    else{
        $sql = "SELECT roomID 
                FROM hotelroom
                WHERE roomID = $roomNumber";
        $query = $connect->query($sql);

        if($query -> rowCount() == 1){
            $sql = "INSERT INTO hotelexpense (roomID,expenseDate,detail,expense) 
                    VALUES ('$roomNumber','$expenseDate','$detail','$expense')"; 
            $query = $connect->query($sql);

            if($query){
                $out['message'] = "Added Successfully";
                $out['success'] = true;
            }
            else{
                $out['message'] = "Could not add";
            }
            echo json_encode($out);
        }
    }
}