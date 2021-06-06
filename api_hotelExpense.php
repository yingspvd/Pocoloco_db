<?php
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if ($request_data->action == "saveData") {

    $employeeID = intval( $request_data->employeeID);
    $roomNumber = intval($request_data->roomNumber);
    $detail = $request_data->detail;
    $expense = floatval($request_data->expense);
    $expenseDate = $request_data->expenseDate;
    $type = intval($request_data -> type) ;    
    
    $sql = "INSERT INTO hotelexpense (employeeID,expenseDate,type,detail,expense) 
            VALUES ('$employeeID','$expenseDate','$type','$detail','$expense')"; 
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

if($request_data->action == "getAll"){
    $year = $request_data->year;
    
    $query="SELECT * 
            FROM expense_view
            WHERE expenseDate LIKE '$year%'
            ORDER BY expenseDate DESC";
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
    $direction = $request_data->direction;
    $year = $request_data->year;

    if($filter == "em_firstname" && $direction == "up"){
        $sql = "SELECT * FROM expense_view
                 WHERE (em_firstname LIKE '$search%' OR em_lastname LIKE '$search%') AND expenseDate LIKE '$year%'
                 ORDER BY $sort DESC
                ";
    }
    else if($filter == "em_firstname" && $direction == "down"){
     $sql = "SELECT * FROM expense_view
              WHERE (em_firstname LIKE '$search%' OR em_lastname LIKE '$search%')AND expenseDate LIKE '$year%'
              ORDER BY $sort 
             ";
     }
    else if($direction == "up"){
        $sql = "SELECT * FROM expense_view
                    WHERE $filter LIKE '$search%' AND expenseDate LIKE '$year%'
                    ORDER BY $sort DESC
                ";
    }
   else if($direction == "down"){
        $sql = "SELECT * FROM expense_view
             WHERE $filter LIKE '$search%'AND expenseDate LIKE '$year%'
             ORDER BY $sort 
            ";
    }
    else{
        $sql = "SELECT * FROM expense_view
                WHERE expenseDate LIKE '$year%'
                ORDER BY expenseDate DESC
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
    echo json_encode($data);  
}

if($request_data->action == "update"){

    $employeeID = intval($request_data -> employeeID);
    $expenseID = intval($request_data->expenseID);
    $type = intval($request_data -> type);
    $detail = $request_data -> detail;
    $expense = floatval($request_data -> expense);
    $expenseDate = $request_data -> expenseDate;


    $sql = "UPDATE hotelexpense 
                SET  employeeID = '$employeeID',
                    type = '$type', 
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