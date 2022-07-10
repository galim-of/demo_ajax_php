<?php
include_once 'database.php';
include_once 'race.php';

header('Content-Type: application/json; charset=utf-8');

$courierID = $_POST['courier_id'];
$regionID = $_POST['region_id'];
$departureTime = $_POST['departure_time'];
$arrivalTime = $_POST['arrival_time'];

$dateFormat = "Y-m-d H:i:s";

if ($courierID == null || $regionID == null || $departureTime == null || $arrivalTime == null) {
    http_response_code(400);
    echo json_encode([
        "error" => "invalid data",
        "want" => ["courier_id" => "int", "region_id" => "int", 
                    "departure_time" => "YYYY-MM-DD hh:mi:ss (string)",
                    "arrival_time" => "YYYY-MM-DD hh:mi:ss (string)"
        ],
    ]);
    return;
}

if (date_create_from_format($dateFormat, $departureTime) == false || date_create_from_format($dateFormat, $arrivalTime) == false) {
    http_response_code(400);
    echo json_encode([
        "error" => "wrong date format",
        "message" => "specify date in YYYY-MM-DD hh:mi:ss format"]);
    return;
}


if ($departureTime == $arrivalTime || $departureTime > $arrivalTime) {
    http_response_code(400);
    echo json_encode(["error" => "arrival_time must be greater than departure_time"]);
    return;
}

if (!canCreateRace($dbh, $courierID, $departureTime, $arrivalTime)) {
    http_response_code(400);
    echo json_encode([
        "code" => "ERR_RANGE",
        "error" => "wrong date range specified",
        "message" => "date range can't overlap other races"]);
    return;
}

$newRace = createRace($dbh, $courierID, $regionID, $departureTime);
if ($newRace) {
    echo json_encode($newRace);
    return;
}
http_response_code(500);
echo json_encode(["error" => "can't create new race"]);
