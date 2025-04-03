<?php
require("config.php");

// Fetch events from your database
function fetchEvents() {
    global $con;
    $events = [];

    $query = "SELECT e.*, u.uname as tenant_name 
              FROM events e 
              LEFT JOIN user u ON e.user_id = u.uid";
    $result = mysqli_query($con, $query);

    while($row = mysqli_fetch_assoc($result)) {
        $events[] = [
            'title' => $row['title'] . ' - ' . $row['tenant_name'],
            'start' => $row['start_datetime'],
            'end' => $row['end_datetime'],
            'description' => $row['description']
        ];
    }
    return $events;
}
if(isset($_GET['get_events'])) {
    header('Content-Type: application/json');
    echo json_encode(fetchEvents());
    exit();
}
?>