<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == "getServiceActivity"){
    
    $sql = "SELECT CAST(DateTime As date) AS date,roomID,SUM(total) AS total
            FROM roomservice
            GROUP BY date,roomID
            ORDER BY DateTime DESC";
                    
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

if($request_data -> action == "getServiceData"){
    
    $roomID = intval($request_data -> roomID) ;
    $date = $request_data -> date;
    
    $sql = "SELECT CAST(DateTime As date) AS date,roomID,s.name,r.amount,s.servicePrice
            FROM roomservice r,servicelist s
            WHERE r.serviceID = s.serviceID AND
                    roomID = '$roomID' AND
                    DateTime LIKE '$date%'
            ORDER BY DateTime
            ";
                    
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

if($request_data->action == 'searchActivity')
{
    $search = $request_data->search;
    $filter = $request_data->filter;
    $sort = $request_data->sort;
    
    if($search == ""){$out=5;
        $sql = "SELECT CAST(DateTime As date) AS date,roomID,SUM(total) AS total
            FROM roomservice
            GROUP BY date,roomID
            ORDER BY date DESC";
    }
    
    else{
        if($filter == "roomID" || $filter == "all"){
            $search = intval($request_data->search) ;
        }
        
        if(($filter == "all" || $filter == "roomID") && ($sort == "all" || $sort == "date"))
        {
            $sql = "SELECT CAST(DateTime As date) AS date,roomID,SUM(total) AS total
                    FROM roomservice
                    WHERE roomID LIKE '$search'
                    GROUP BY date,roomID
                    ORDER BY DateTime DESC 
                    ";
        }
    
        if($filter == "date" && ($sort == "all" || $sort == "date"))
        {
            $sql = "SELECT CAST(DateTime As date) AS date,roomID,SUM(total) AS total
                    FROM roomservice
                    WHERE DateTime LIKE '$search%'
                    GROUP BY date,roomID
                    ORDER BY DateTime DESC 
                    ";
        }
    
        if($filter == "date" && $sort == "roomID" )
        {
            $sql = "SELECT CAST(DateTime As date) AS date,roomID,SUM(total) AS total
                    FROM roomservice
                    WHERE DateTime LIKE '$search%'
                    GROUP BY date,roomID
                    ORDER BY roomID
                    ";
        }
    
        if(($filter == "all" || $filter == "roomID") && $sort == "roomID" )
        {
            $sql = "SELECT CAST(DateTime As date) AS date,roomID,SUM(total) AS total
                    FROM roomservice
                    WHERE roomID LIKE '$search'
                    GROUP BY date,roomID
                    ORDER BY roomID 
                    ";
        }
        
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