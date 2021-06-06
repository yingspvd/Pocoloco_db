<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == "getPromotion"){
    $sql = "SELECT *
            FROM promotion_view
            ORDER BY startDate DESC";
                    
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

if($request_data->action=="searchData"){
    $search = $request_data->search;
    $filter = $request_data->filter;
    $sort = $request_data->sort;
    $direction = $request_data->direction;
    
    if( ($filter == "promotionName" || $filter == "seasonName") && $direction == "up"){
        $sql = "SELECT *
                FROM promotion_view
                WHERE $filter LIKE '%$search%'
                ORDER BY $sort DESC";
    }
    else if(($filter == "promotionName" || $filter == "seasonName") && $direction == "down"){
        $sql = "SELECT *
                FROM promotion_view
                WHERE $filter LIKE '%$search%'
                ORDER BY $sort";
    }
    
    else if($direction == "up"){
        $sql = "SELECT *
                FROM promotion_view
                WHERE $filter LIKE '$search%'
                ORDER BY $sort DESC";
    }
    else if($direction == "down"){
        $sql = "SELECT *
                FROM promotion_view
                WHERE $filter LIKE '$search%'
                ORDER BY $sort";
    }
    else{
        $sql = "SELECT *
        FROM promotion_view
        ORDER BY startDate DESC";
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

if($request_data -> action == "getRoomType"){
  
    // query
    $sql = "SELECT roomTypeID,roomType FROM roomdescription";
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
  
    echo json_encode($data);
  }

  if($request_data -> action == "getSeason"){
  
    // query
    $sql = "SELECT seasonID,seasonName FROM season";
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
  
    echo json_encode($data);
  }
  
  if($request_data -> action == "editData"){
    
    $promotionID = $request_data -> promotionID;
    
    $sql = "SELECT p.promotionID,s.seasonName,p.promotionName,r.roomType,p.discount, p.startDate, p.endDate
            FROM promotion p, season s, roomdescription r
            WHERE promotionID = '$promotionID' AND
                p.seasonID = s.seasonID AND
                p.roomTypeID = r.roomTypeID";
    
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    
    echo json_encode($data);  
}

if($request_data -> action == "updateData"){
    $promotionID = $request_data -> promotionID;
    $promotion = $request_data -> promotion;
    $season = $request_data -> season;
    $startDate = $request_data -> startDate;
    $endDate = $request_data -> endDate;
    $roomType = $request_data -> roomType;
    $discount = $request_data -> discount;
    
    $sql_value = "SELECT s.seasonID,r.roomTypeID
                    FROM season s, roomdescription r
                    WHERE s.seasonName = '$season' AND r.roomType = '$roomType'"; 
    $query = $connect->query($sql_value);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $seasonID = $row['seasonID'];
        $roomTypeID = $row['roomTypeID'];
    } 
                    
    $sql = "UPDATE promotion 
            SET promotionName = '$promotion', seasonID = '$seasonID', roomTypeID = '$roomTypeID', 
                startDate = '$startDate', endDate = '$endDate' , discount = '$discount'
            WHERE promotionID = '$promotionID'";
    $query = $connect->query($sql);

    if($query){
        $out['message'] = "Updated Successfully";
        $out['success'] = true;
        }
        else{
        $out['message'] = "Could not update ";
        }

    echo json_encode($out); 
}

if($request_data->action == "deleteData"){
  
    $promotionID = $request_data -> promotionID;
    
    $sql = "DELETE FROM promotion WHERE promotionID = '$promotionID'";
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