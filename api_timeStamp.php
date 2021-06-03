<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == 'timeStamp'){
    $employeeID = intval($request_data -> employeeID) ;
    $type = $request_data -> type;
    
    $sql = "INSERT INTO timestamp (employeeID,type)
            VALUES('$employeeID','$type')";

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
                WHERE $searchFilter LIKE '$keyword%' 
                ORDER BY $sortFilter DESC";
    } else { 
        $sql="SELECT *
                FROM allTimestamp_view
                WHERE $searchFilter LIKE '$keyword%' 
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