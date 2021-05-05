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

if($request_data->action=="getAll"){
    $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                h.roomID, d.roomType, h.expense, h.expenseDate
            FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
            WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID
            GROUP BY h.expenseID";
    $statement=$connect->prepare($query);
    $statement->execute();  //ไม่มี data ไม่ได้โยนข้อมูลไป
    // loop เก็บข้อมูลลงไปใน data 
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        $data[]=$row;
    }
    
    echo json_encode($data);   //table
}

if($request_data->action=="SearchData"){
    $search = $request_data->search;
    $sort = $request_data -> sort;
    $filter = $request_data -> filter;

    if($sort == "all"  && $filter == "all" ){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    (e.em_firstname LIKE '$search%' OR e.em_lastname LIKE '$search%' 
                    OR h.roomID LIKE '$search%' OR d.roomType LIKE '$search%' 
                    OR h.expenseDate  LIKE '$search%') 
                GROUP BY h.expenseID
                ORDER BY h.expenseDate DESC, h.roomID, h.expense DESC,d.roomTypeID";

    }
    
    else if($sort == "roomID"  && $filter == "all"){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                h.roomID, d.roomType, h.expense, h.expenseDate
            FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
            roomdescription d ON r.roomTypeID = d.roomTypeID
            WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                (e.em_firstname LIKE '$search%' OR e.em_lastname LIKE '$search%' 
                OR h.roomID LIKE '$search%' OR d.roomType LIKE '$search%' 
                OR h.expenseDate  LIKE '$search%') 
            GROUP BY h.expenseID
            ORDER BY h.$sort";
    }
    else if($sort == "roomType" && $filter == "all"){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                h.roomID, d.roomType, h.expense, h.expenseDate
            FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
            roomdescription d ON r.roomTypeID = d.roomTypeID
            WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                (e.em_firstname LIKE '$search%' OR e.em_lastname LIKE '$search%' 
                OR h.roomID LIKE '$search%' OR d.roomType LIKE '$search%' 
                OR h.expenseDate  LIKE '$search%') 
            GROUP BY h.expenseID
            ORDER BY d.$sort";
    }

    else if($sort == "employeeName" && $filter == "all"){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                h.roomID, d.roomType, h.expense, h.expenseDate
            FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
            roomdescription d ON r.roomTypeID = d.roomTypeID
            WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                (e.em_firstname LIKE '$search%' OR e.em_lastname LIKE '$search%' 
                OR h.roomID LIKE '$search%' OR d.roomType LIKE '$search%' 
                OR h.expenseDate  LIKE '$search%') 
            GROUP BY h.expenseID
            ORDER BY e.em_firstname";
    }
    else if(($sort == "expense" || $sort == "expenseDate") && $filter == "all"){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                h.roomID, d.roomType, h.expense, h.expenseDate
            FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
            roomdescription d ON r.roomTypeID = d.roomTypeID
            WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                (e.em_firstname LIKE '$search%' OR e.em_lastname LIKE '$search%' 
                OR h.roomID LIKE '$search%' OR d.roomType LIKE '$search%' 
                OR h.expenseDate  LIKE '$search%') 
            GROUP BY h.expenseID
            ORDER BY h.$sort DESC";
    }
    else if(($sort == "all") && ($filter == "roomID" )){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( h.roomID LIKE '$search%') 
                GROUP BY h.expenseID
                ORDER BY h.expenseDate DESC, h.roomID, h.expense DESC,d.roomTypeID";
    }
    else if(($sort == "all") && ($filter == "roomType")){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( d.roomType LIKE '$search%' ) 
                GROUP BY h.expenseID
                ORDER BY h.expenseDate DESC, h.roomID, h.expense DESC,d.roomTypeID";
    }
    else if(($sort == "all") && ($filter == "employeeName")){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( e.em_firstname LIKE '$search%' OR e.em_lastname LIKE '$search%' ) 
                GROUP BY h.expenseID
                ORDER BY h.expenseDate DESC, h.roomID, h.expense DESC,d.roomTypeID";
    }
    else if(($sort == "all") && ($filter == "expense" || $filter == "expenseDate")){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( h.$filter LIKE '$search%' ) 
                GROUP BY h.expenseID
                ORDER BY h.expenseDate DESC, h.roomID, h.expense DESC,d.roomTypeID";
    }
    else if(($sort == "roomID") && ($filter == "roomID" )){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( h.roomID LIKE '$search%') 
                GROUP BY h.expenseID
                ORDER BY h.$sort";
    }
    else if(($sort == "roomID") && ($filter == "roomType" )){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( d.roomType LIKE '$search%') 
                GROUP BY h.expenseID
                ORDER BY h.$sort";
    }
    else if(($sort == "roomID") && ($filter == "employeeName" )){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( e.em_firstname LIKE '$search%' OR e.em_lastname LIKE '$search%') 
                GROUP BY h.expenseID
                ORDER BY h.$sort";
    }
    else if(($sort == "roomType") && ($filter == "expense" || $filter == "expenseDate")){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    (  h.$filter LIKE '$search%' ) 
                GROUP BY h.expenseID
                ORDER BY d.$sort";
    }
    else if(($sort == "roomType") && ($filter == "roomID" )){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( h.roomID LIKE '$search%') 
                GROUP BY h.expenseID
                ORDER BY d.$sort";
    }
    else if(($sort == "roomType") && ($filter == "roomType" )){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( d.roomType LIKE '$search%') 
                GROUP BY h.expenseID
                ORDER BY d.$sort";
    }
    else if(($sort == "roomType") && ($filter == "employeeName" )){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( e.em_firstname LIKE '$search%' OR e.em_lastname LIKE '$search%') 
                GROUP BY h.expenseID
                ORDER BY d.$sort";
    }
    else if(($sort == "roomType") && ($filter == "expense" || $filter == "expenseDate")){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    (  h.$filter LIKE '$search%' ) 
                GROUP BY h.expenseID
                ORDER BY d.$sort";
    }
    else if(($sort == "employeeName") && ($filter == "roomID" )){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( h.roomID LIKE '$search%') 
                GROUP BY h.expenseID
                ORDER BY e.em_firstname";
    }
    else if(($sort == "employeeName") && ($filter == "roomType" )){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( d.roomType LIKE '$search%') 
                GROUP BY h.expenseID
                ORDER BY e.em_firstname";
    }
    else if(($sort == "employeeName") && ($filter == "employeeName" )){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( e.em_firstname LIKE '$search%' OR e.em_lastname LIKE '$search%') 
                GROUP BY h.expenseID
                ORDER BY e.em_firstname";
    }
    else if(($sort == "employeeName") && ($filter == "expense" || $filter == "expenseDate")){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    (  h.$filter LIKE '$search%' ) 
                GROUP BY h.expenseID
                ORDER BY e.em_firstname";
    }
    else if(($sort == "expense" || $sort == "expenseDate") && ($filter == "roomID" )){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( h.roomID LIKE '$search%') 
                GROUP BY h.expenseID
                ORDER BY h.$sort DESC";
    }
    else if(($sort == "expense" || $sort == "expenseDate")&& ($filter == "roomType" )){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( d.roomType LIKE '$search%') 
                GROUP BY h.expenseID
                ORDER BY h.$sort DESC";
    }
    else if(($sort == "expense" || $sort == "expenseDate") && ($filter == "employeeName" )){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    ( e.em_firstname LIKE '$search%' OR e.em_lastname LIKE '$search%') 
                GROUP BY h.expenseID
                ORDER BY h.$sort DESC";
    }
    else if(($sort == "expense" || $sort == "expenseDate") && ($filter == "expense" || $filter == "expenseDate")){
        $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    h.roomID, d.roomType, h.expense, h.expenseDate
                FROM employee e, hotelexpense h, hotelroom r LEFT JOIN
                roomdescription d ON r.roomTypeID = d.roomTypeID
                WHERE h.employeeID = e.employeeID AND h.roomID = r.roomID AND 
                    (  h.$filter LIKE '$search%' ) 
                GROUP BY h.expenseID
                ORDER BY h.$sort DESC";
    }
    
    $statement=$connect->prepare($query);
    $statement->execute();  //ไม่มี data ไม่ได้โยนข้อมูลไป
    // loop เก็บข้อมูลลงไปใน data 
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        $data[]=$row;
    }
    if($statement->rowCount() == 0)
    {
        $data = "";
    }
    
    echo json_encode($data);   //table
}

if($request_data->action=="getEditData"){
    $query="SELECT h.expenseID,e.employeeID,concat(e.em_firstname,' ', e.em_lastname) AS employeeName, 
                    r.roleName, h.roomID, h.detail, h.expense, h.expenseDate
            FROM employee e, role r, hotelexpense h
            WHERE h.expenseID = $request_data->expenseID AND h.employeeID = e.employeeID AND e.roleID = r.roleID
            GROUP BY h.expenseID";
    $statement=$connect->prepare($query);
    $statement->execute(); 
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        // เปลี่ยนจาก array เป็นobject
        $data['expenseID']=$row['expenseID'];
        $data['employeeID']=$row['employeeID'];
        $data['employeeName']=$row['employeeName'];
        $data['employeeRole']=$row['roleName'];
        $data['roomID']=$row['roomID'];
        $data['detail']=$row['detail'];
        $data['expense']=$row['expense'];
        $data['expenseDate']=$row['expenseDate'];
    }
    
    echo json_encode($data);   //table
}

if($request_data->action == "update"){
    //จัดเตรียมข้อมูล
    $data = array(":expenseID" => $request_data->expenseID,
                ":roomID" => $request_data -> roomID,
                ":detail" => $request_data -> detail,
                ":expense" => $request_data -> expense,
                ":expenseDate" => $request_data -> expenseDate,);
    if (is_numeric($expense) == false){
        $out['message'] = "Expense is not correct";
        echo json_encode($out);
    }
    else{
        $sql = "SELECT roomID 
                FROM hotelroom
                WHERE roomID = $roomID";
        $query = $connect->query($sql);
        if($query -> rowCount() == 1){
            $query = "UPDATE hotelexpense 
                        SET  roomID = :roomID, detail = :detail, expense = :expense, 
                            expenseDate = :expenseDate 
                        WHERE expenseID = :expenseID";
            $statement = $connect -> prepare($query);
            $statement -> execute($data);
            $output = array("message" => "Update Complete");
            echo json_encode($output);
        }
    }               
}

if($request_data->action == "deleteData"){
    $query = "DELETE FROM hotelexpense WHERE expenseID = $request_data->expenseID";
    $statement = $connect -> prepare($query);
    $statement -> execute();
    $output = array("message" => "Delete Complete");
    echo json_encode($output);
}
?>