<?php 
require_once 'connect.php';
$request_data=json_decode(file_get_contents("php://input"));

if($request_data -> action == "getTotalEarning"){

    $date = $request_data -> date;

    $sql = "SELECT sum(amountPaid) AS totalEarning
            FROM payment
            WHERE datePaid LIKE '$date%' AND type = 2";

   $query = $connect->query($sql);
            
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    echo json_encode($data);

}

if($request_data -> action == "getTotalBooking"){

    $date = $request_data -> date;

    $sql = "SELECT count(bookingDetailID) AS numBookingDetail
            FROM bookingdetail
            WHERE dateTime LIKE '$date%'";

   $query = $connect->query($sql);
            
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    echo json_encode($data);

}
if($request_data -> action == "getTotalOrder"){

    $date = $request_data -> date;

    $sql = "SELECT count(serviceID) AS numOrder
            FROM roomservice
            WHERE DateTime LIKE '$date%'";

   $query = $connect->query($sql);
            
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    echo json_encode($data);

}

if($request_data -> action == "getTotalCustomer"){

    $date = $request_data -> date;

    $sql = "SELECT count(customerID) AS numCustomer
            FROM customer
            WHERE dateTime LIKE '$date%'";

   $query = $connect->query($sql);
            
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    echo json_encode($data);

}
if($request_data -> action == "getEarning"){

    $year = $request_data -> year;

    $sql = "SELECT sum(amountPaid) AS summary, EXTRACT(MONTH FROM datePaid) AS month
            FROM payment
            WHERE datePaid LIKE '$year%'
            GROUP BY MONTH(datePaid)";

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

if($request_data -> action == "getExpense"){

    $year = $request_data -> year;

    $sql = "SELECT sum(expense) AS summary, EXTRACT(MONTH FROM expenseDate) AS month
            FROM hotelexpense
            WHERE expenseDate LIKE '$year%'
            GROUP BY MONTH(expenseDate)";

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

if($request_data -> action == "getCancel"){

    $year = $request_data -> year;

    $sql = "SELECT count(bookingDetailID) AS num, EXTRACT(MONTH FROM dateTime) AS month
            FROM bookingdetail
            WHERE dateTime LIKE '$year%' AND status = 'C'
            GROUP BY MONTH(dateTime)";

   $query = $connect->query($sql);
            
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    echo json_encode($data);

}

if($request_data -> action == "getGuest"){

    $year = $request_data -> year;

    $sql = "SELECT COUNT(DISTINCT bookingID) AS num, EXTRACT(MONTH FROM dateTime) AS month
            FROM bookingdetail
            WHERE checkIn LIKE '$year%'
            GROUP BY MONTH(dateTime)";

   $query = $connect->query($sql);
            
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    echo json_encode($data);

}

if($request_data -> action == "getRoomReservation"){
    $year = $request_data -> year;

    $sql = "SELECT roomType, COUNT(DISTINCT bookingDetailID) AS num
            FROM bookingdetail_view
            WHERE dateTime LIKE '$year%'
            GROUP BY roomType";

   $query = $connect->query($sql);
            
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    if($query->rowCount() == 0)
    {
        $data = null;
    }
    echo json_encode($data);

}

if($request_data -> action == "getMontlyExpenses"){
    
    $date = $request_data -> date;

    $sql = "SELECT e.type, sum(v.expense) AS expense
            FROM expense_view v, hotelexpense e
            WHERE e.dateTime LIKE '$date%' AND e.expenseID = v.expenseID
            GROUP BY e.type
            ORDER BY e.type";

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


if($request_data -> action == "getRoomService"){
    $year = $request_data -> year;
    $type = intval($request_data -> type);

    $sql = "SELECT sum(r.amount) AS numService,r.serviceID
            FROM roomservice r,servicelist s
            WHERE dateTime LIKE '$year%' AND r.serviceID = s.serviceID AND s.type = $type
            GROUP BY r.serviceID
            ORDER BY numService DESC";

   $query = $connect->query($sql);
            
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
    echo json_encode($data);
}

if($request_data -> action == "getAbsence"){
    $year = $request_data -> year;

    $sql = "SELECT count(employeeID) AS numEmployee
            FROM employee";

    $query = $connect->query($sql);
       
    while($row = $query -> fetch(PDO::FETCH_ASSOC)){
        $numEmployee = $row['numEmployee'];
    }
    $numEmployee = intval($numEmployee);

    $sql = "SELECT ($numEmployee - count(employeeID)) AS numEmployee, 
                EXTRACT(DAY FROM StampDateTime) AS day,
                EXTRACT(MONTH FROM StampDateTime) AS month,
                EXTRACT(YEAR FROM StampDateTime) AS year
            FROM timestamp 
            WHERE StampDateTime LIKE '$year%'
            GROUP BY CAST(StampDateTime AS DATE)
            ORDER BY numEmployee DESC
            LIMIT 6";

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

if($request_data -> action == "getLateEmployee"){
    $year = intval($request_data -> year);

    $sql = "SELECT * 
        FROM lateEmployee_view
        WHERE EXTRACT(YEAR FROM stampDateTime) = $year";
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