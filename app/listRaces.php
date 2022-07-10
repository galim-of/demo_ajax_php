<?php
include_once 'database.php';
include_once 'race.php';

header('Content-Type: application/json; charset=utf-8');
$dateFormat = "Y-m-d";
$departure = $_GET['departure'];
$arrival = $_GET['arrival'];
$minDate = '1970-01-01';
$maxDate = '2100-12-30';

if ($departure == null && $arrival == null) {
    $departure = $minDate;
    $arrival = $maxDate;
} else {
    $arrival = $arrival ? $arrival : $maxDate;
    $departure = $departure ? $departure : date($dateFormat);
    if (date_create_from_format($dateFormat, $departure) == false || date_create_from_format($dateFormat, $arrival) == false) {
        http_response_code(400);
        echo json_encode([
            "error" => "wrong date format",
            "message" => "specify date in YYYY-MM-DD format"]);
        return;
    }
}
listRacesInJson($dbh, $departure, $arrival);
