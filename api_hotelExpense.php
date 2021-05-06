<?php
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if ($request_data->action == "saveData") {

    $employeeID = intval( $request_data->employeeID);
    $roomNumber = intval($request_data->roomNumber);
    $detail = $request_data->detail;
    $expense = floatval($request_data->expense) ;
    $expenseDate = $request_data->expenseDate;

    
    $sql = "SELECT h.roomID,e.employeeID
            FROM hotelroom h, employee e
            WHERE h.roomID =$roomNumber AND e.employeeID = $employeeID
            ";
    $query = $connect->query($sql);
    
    if($query -> rowCount() == 1){
        
        $sql = "INSERT INTO hotelexpense (employeeID,roomID,expenseDate,detail,expense) 
                VALUES ('$employeeID','$roomNumber','$expenseDate','$detail','$expense')"; 
        $query = $connect->query($sql);

        if($query){
            $out['message'] = "Added Successfully";
            $out['success'] = true;
        }
        else{
            $out['message'] = "Could not add";
            }
            
        }
    echo json_encode($out);
}

if($request_data->action == "getAll"){
    $query="SELECT * FROM expense_view";
    $statement=$connect->prepare($query);
    $statement->execute();  
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        $data[]=$row;
    }

    if($statement->rowCount() == 0)
    {
        $data = "";
    } 
    
    echo json_encode($data);  
}

if($request_data -> action == "searchData"){
    $search = $request_data->search;
    $sort = $request_data -> sort;
    $filter = $request_data -> filter;

   if($filter == "all" && $sort == "all"){
       $sql = "SELECT * FROM expense_view
                WHERE em_firstname LIKE '$search%' OR 
                    em_lastname LIKE '$search%' OR
                    roomID LIKE '$search%' OR 
                    roomType LIKE '$search%' OR
                    expenseDate  LIKE '$search%'
                ORDER BY expenseDate DESC, roomID, expense DESC,roomType
               ";
   }
   elseif($filter != "all" && $sort == "all"){
        $sql = "SELECT * FROM expense_view
                WHERE $filter LIKE '$search%'
                ORDER BY expenseDate DESC, roomID, expense DESC,roomType
                ";
   }
   elseif($filter == "all" && ($sort == "expenseDate" || $sort == "expense")){
        $sql = "SELECT * FROM expense_view
                WHERE em_firstname LIKE '$search%' OR 
                    em_lastname LIKE '$search%' OR
                    roomID LIKE '$search%' OR 
                    roomType LIKE '$search%' OR
                    expenseDate  LIKE '$search%'
                ORDER BY $sort DESC
                ";
   }
   elseif($filter == "all" && ($sort != "expenseDate" && $sort != "expense")){
        $sql = "SELECT * FROM expense_view
                WHERE em_firstname LIKE '$search%' OR 
                    em_lastname LIKE '$search%' OR
                    roomID LIKE '$search%' OR 
                    roomType LIKE '$search%' OR
                    expenseDate  LIKE '$search%'
                ORDER BY $sort
                ";
    }
    elseif($filter != "all" && ($sort == "expenseDate" || $sort == "expense")){
        $sql = "SELECT * FROM expense_view
                WHERE $filter LIKE '$search%' 
                ORDER BY $sort DESC
                ";
   }
    elseif($filter != "all" && ($sort != "expenseDate" && $sort != "expense")){
        $sql = "SELECT * FROM expense_view
                WHERE $filter LIKE '$search%' 
                ORDER BY $sort
                ";
    }
    else{
        $sql = "SELECT * FROM expense_view
                WHERE $filter LIKE '$search%'
                ORDER BY $sort
                ";
    }

    $query = $connect->query($sql);
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    
    if($query->rowCount() == 0)
    {
        $data = "";
    } 
    
    echo json_encode($data);  
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

    $employeeID = intval($request_data -> employeeID);
    $expenseID = intval($request_data->expenseID);
    $roomID = intval($request_data -> roomID);
    $detail = $request_data -> detail;
    $expense = floatval($request_data -> expense);
    $expenseDate = $request_data -> expenseDate;

    $sql = "SELECT h.roomID,e.employeeID
            FROM hotelroom h, employee e
            WHERE h.roomID = $roomID AND e.employeeID = $employeeID
            ";
    $query = $connect->query($sql);
    
    if($query -> rowCount() == 1){
        $sql = "UPDATE hotelexpense 
                    SET  employeeID = '$employeeID',
                        roomID = '$roomID', 
                        detail = '$detail', 
                        expense = '$expense', 
                        expenseDate = '$expenseDate'
                    WHERE expenseID = '$expenseID'";
        $query = $connect->query($sql);
        
        if($query){
            $out['message'] = "Updated Successfully";
            $out['success'] = true;
            }
        else{
            $out['message'] = "Could not delete ";
        }
        
    }
   echo json_encode($out);           
}

if($request_data->action == "deleteData"){
    $sql = "DELETE FROM hotelexpense WHERE expenseID = $request_data->expenseID";
    
    $query = $connect->query($sql);

    if($query){
        $out['message'] = "Deleted Successfully";
        $out['success'] = true;
        }
    else{
        $out['message'] = "Could not delete ";
    }
    echo json_encode($out);
    
}
?>