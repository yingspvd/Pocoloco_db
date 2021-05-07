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

if($request_data->action=="SearchData"){
    $search = $request_data->search;
    $sort = $request_data -> sort;
    $filter = $request_data -> filter;

    if(($sort == "all" || $sort == "roomID")  && $filter == "all"){
        $query="SELECT r.roomID, d.roomType, d.roomPrice, d.capacity, d.size
                FROM hotelroom r, roomdescription d
                WHERE r.roomTypeID = d.roomTypeID AND (r.roomID LIKE '$search%' OR d.roomType LIKE '$search%' 
                                                        or d.roomPrice LIKE '$search%')
                GROUP BY r.roomID
                ORDER BY r.roomID";
    }
    else if($sort != "all"  && $filter == "all"){
        $query="SELECT r.roomID, d.roomType, d.roomPrice, d.capacity, d.size
                FROM hotelroom r, roomdescription d
                WHERE r.roomTypeID = d.roomTypeID AND (r.roomID LIKE '$search%' OR d.roomType LIKE '$search%' 
                                                        or d.roomPrice LIKE '$search%')
                GROUP BY r.roomID
                ORDER BY d.$sort";
    }
    else if(($sort == "all" || $sort == "roomID") && $filter == "roomID"){
        $query="SELECT r.roomID, d.roomType, d.roomPrice, d.capacity, d.size
                FROM hotelroom r, roomdescription d
                WHERE r.roomTypeID = d.roomTypeID AND (r.roomID LIKE '$search%')
                GROUP BY r.roomID
                ORDER BY r.roomID";
    }
    else if($sort != "all" && $filter == "roomID"){
        $query="SELECT r.roomID, d.roomType, d.roomPrice, d.capacity, d.size
                FROM hotelroom r, roomdescription d
                WHERE r.roomTypeID = d.roomTypeID AND (r.roomID LIKE '$search%')
                GROUP BY r.roomID
                ORDER BY d.$sort";
    }
    else if(($sort == "all" || $sort == "roomID") && ($filter != "roomID" || $filter != "all")){
        $query="SELECT r.roomID, d.roomType, d.roomPrice, d.capacity, d.size
                FROM hotelroom r, roomdescription d
                WHERE r.roomTypeID = d.roomTypeID AND (d.$filter LIKE '$search%')
                GROUP BY r.roomID
                ORDER BY r.roomID";
    }
    else if($sort != "all" && ($filter != "roomID" || $filter != "all")){
        $query="SELECT r.roomID, d.roomType, d.roomPrice, d.capacity, d.size
                FROM hotelroom r, roomdescription d
                WHERE r.roomTypeID = d.roomTypeID AND (d.$filter LIKE '$search%')
                GROUP BY r.roomID
                ORDER BY d.$sort";
    }
    
    $statement=$connect->prepare($query);
    $statement->execute();  
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
    $query="SELECT r.roomID, d.roomType, d.roomPrice, d.capacity
            FROM hotelroom r, roomdescription d
            WHERE r.roomID = $request_data->roomID AND r.roomTypeID = d.roomTypeID";
    $statement=$connect->prepare($query);
    $statement->execute(); 
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        // เปลี่ยนจาก array เป็นobject
        $data['roomID']=$row['roomID'];
        $data['roomType']=$row['roomType'];
        $data['roomPrice']=$row['roomPrice'];
        $data['capacity']=$row['capacity'];
        
    }
    
    echo json_encode($data);   //table
}

if($request_data->action == "update"){
    //จัดเตรียมข้อมูล
    $data = array(":roomID" => $request_data -> roomID,
                ":roomType" => $request_data -> roomType,
                ":roomPrice" => $request_data -> roomPrice,
                ":capacity" => $request_data -> capacity,);
    $query = "UPDATE hotelroom r, roomdescription d SET  r.roomTypeID = :roomType, d.roomPrice = :roomPrice, d.capacity = :capacity 
    WHERE roomID = :roomID";
    $statement = $connect -> prepare($query);
    $statement -> execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output);
                
}

// if($request_data->action == "deleteData"){
//     $query = "DELETE FROM hotelexpense WHERE expenseID = $request_data->expenseID";
//     $statement = $connect -> prepare($query);
//     $statement -> execute();
//     $output = array("message" => "Delete Complete");
//     echo json_encode($output);
// }
?>