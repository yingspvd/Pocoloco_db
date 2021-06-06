<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == 'stampChecIn'){
    $employeeID = intval($request_data -> employeeID) ;
    $type = $request_data -> type;
    
    $sql = "SELECT shift FROM `employee` WHERE employeeID = $employeeID";
    $query = $connect->query($sql);
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
        $shift =$row["shift"];
    }

    if($shift == 1){
        $sql = "SELECT  CAST(CURRENT_TIMESTAMP as time) >= '03:00:00' AND 
                CAST(CURRENT_TIMESTAMP as time) < '05:00:00' AS checklate";
    }
    else if($shift == 2){
        $sql = "SELECT  CAST(CURRENT_TIMESTAMP as time) >= '11:00:00' AND 
                CAST(CURRENT_TIMESTAMP as time) < '13:00:00' AS checklate";
    }
    else if($shift == 3){
        $sql = "SELECT  CAST(CURRENT_TIMESTAMP as time) >= '19:00:00' AND 
                CAST(CURRENT_TIMESTAMP as time) < '21:00:00' AS checklate";
    }
    $query = $connect->query($sql);
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
        $checklate =$row["checklate"];
    }

    $checklate = intval($checklate);

    $sql = "INSERT INTO timestamp (employeeID,type,late)
            VALUES('$employeeID','$type','$checklate')";

    $query = $connect->query($sql);   
    
    if($query){
        $data["success"] = true;
    }
    else{
        $data["success"] = false;
    }

    echo json_encode($data);
}

if($request_data -> action == 'stampCheckOut'){
    $employeeID = intval($request_data -> employeeID) ;
    $type = $request_data -> type;
    
    $sql = "SELECT shift FROM `employee` WHERE employeeID = $employeeID";
    $query = $connect->query($sql);
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
        $shift =$row["shift"];
    }

    if($shift == 1){
        $sql = "SELECT  CAST(CURRENT_TIMESTAMP as time) >= '05:00:00' AND 
                CAST(CURRENT_TIMESTAMP as time) < '13:00:00' AS checklate";
    }
    else if($shift == 2){
        $sql = "SELECT  CAST(CURRENT_TIMESTAMP as time) >= '13:00:00' AND 
                CAST(CURRENT_TIMESTAMP as time) < '21:00:00' AS checklate";
    }
    else if($shift == 3){
        $sql = "SELECT  CAST(CURRENT_TIMESTAMP as time) >= '21:00:00' AND 
                CAST(CURRENT_TIMESTAMP as time) < '05:00:00' AS checklate";
    }
    $query = $connect->query($sql);
    while($row = $query->fetch(PDO::FETCH_ASSOC)){
        $checklate =$row["checklate"];
    }

    $checklate = intval($checklate);

    $sql = "INSERT INTO timestamp (employeeID,type,late)
            VALUES('$employeeID','$type','$checklate')";

    $query = $connect->query($sql);   
    
    if($query){
        $data["success"] = true;
    }
    else{
        $data["success"] = false;
    }

    echo json_encode($data);
}

if ($request_data -> action == "getTodayTimeStamp") {
    $today = $request_data -> today;
    
    $sql = "SELECT *
        FROM allTimestamp_view
        WHERE stampDateTime BETWEEN '$today 00:00:00' AND '$today 23:59:59'";

    $query = $connect -> query($sql);
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    
    if($query->rowCount() == 0)
    {
        $data = "";
    }
    
    echo json_encode($data); 
}

if ($request_data -> action == "searchData") {
    $keyword = $request_data->keyword;
    $searchFilter = $request_data->searchFilter;
    $sortFilter = $request_data->sortFilter;
    $direction = $request_data->direction;

    if($direction == "up"){  
         
        $sql="SELECT *
                FROM allTimestamp_view
                WHERE $searchFilter LIKE '$keyword%' AND 
                (stampDateTime > (now() - interval 1 month) AND stampDateTime < now()) 
                ORDER BY $sortFilter DESC";
    } else { 
        
        $sql="SELECT *
                FROM allTimestamp_view
                WHERE $searchFilter LIKE '$keyword%' AND 
                (stampDateTime > (now() - interval 1 month) AND stampDateTime < now())
                ORDER BY $sortFilter";
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

?>