<?php
require("config.php");

$monthlyQuery = "
    SELECT DATE_FORMAT(date_created, '%Y-%m') AS month, SUM(amount) AS total_income
    FROM payment
    GROUP BY month
    ORDER BY month ASC
    ";

$monthlyQueryResult = $con->query($monthlyQuery);

$monthlyData = [];
while ($row = $monthlyQueryResult->fetch_assoc()) {
    $monthlyData[] = $row;
}

$overallQuery = "SELECT SUM(amount) AS overall_income FROM payment";
$overallQueryResult = $con->query($overallQuery);
$overallIncome = $overallQueryResult->fetch_assoc()['overall_income'];

$con->close();
$monthlyDataJSON = json_encode($monthlyData);
$overallDataJSON = json_encode($overallIncome);