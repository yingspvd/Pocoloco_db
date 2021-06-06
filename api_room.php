<?php
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));
$data = array();

if($request_data -> action == "getRoomType"){

    // query
    $sql = "SELECT roomTypeID,roomType FROM roomdescription";
    $query = $connect->query($sql);
  
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
  
    echo json_encode($data);
  }

if($request_data->action=="getAll"){
    $query="SELECT r.roomID, d.roomType, d.roomPrice, d.capacity, d.size
    FROM hotelroom r, roomdescription d
    WHERE r.roomTypeID = d.roomTypeID";
    $statement=$connect->prepare($query);
    $statement->execute();  //ไม่มี data ไม่ได้โยนข้อมูลไป
    // loop เก็บข้อมูลลงไปใน data 
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        $data[]=$row;
    }
    
    echo json_encode($data);   //table
}

if($request_data->action=="searchData"){
    $search = $request_data->search;
    $sort = $request_data -> sort;
    $filter = $request_data -> filter;
    $direction = $request_data -> direction;
     
    if($direction == "up"){
        $out = 1;
        $sql="SELECT *
                FROM room_view
                WHERE $filter LIKE '$search%' 
                ORDER BY $sort DESC";
    }
    else if($direction == "down"){
        $out = 2;
        $sql="SELECT *
                FROM room_view
                WHERE $filter LIKE '$search%' 
                ORDER BY $sort";
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


if($request_data->action == "updateData"){

    $roomID = $request_data -> roomID;
    $roomType = $request_data -> roomType;
    $roomPrice = $request_data -> roomPrice;
    $capacity = $request_data -> capacity;
    
    $sql = "UPDATE hotelroom h, roomdescription d 
            SET  h.roomTypeID = (SELECT roomTypeID FROM roomdescription WHERE roomType = '$roomType'), 
                d.roomPrice = $roomPrice, 
                d.capacity = $capacity
            WHERE h.roomID = $roomID AND 
                d.roomTypeID = (SELECT roomTypeID FROM roomdescription WHERE roomType = '$roomType')
            ";
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

?>