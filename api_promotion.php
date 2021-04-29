<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == "getPromotion"){
    $sql = "SELECT p.promotionID,s.seasonName,p.promotionName,r.roomType,p.discount, p.startDate, p.endDate
            FROM promotion p, season s, roomdescription r
            WHERE p.seasonID = s.seasonID AND
                p.roomTypeID = r.roomTypeID
            ORDER BY p.startDate, s.seasonName";
                    
    $query = $connect->query($sql);
    
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    
    echo json_encode($data);   
} 

if($request_data->action=="searchData"){
    $search = $request_data->search;
    
    $sql="SELECT p.promotionID,s.seasonName,p.promotionName,r.roomType,p.discount, p.startDate, p.endDate
            FROM promotion p, season s, roomdescription r
            WHERE p.seasonID = s.seasonID AND
                p.roomTypeID = r.roomTypeID AND
                (s.seasonName LIKE '$search%' OR p.promotionName LIKE '$search%' OR r.roomType LIKE '$search%')
            ORDER BY p.startDate, s.seasonName, r.roomType";
            
    $query = $connect->query($sql);
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
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