<?php
function createRace(&$pdo, $courierID, $regionID, $departureTime)
{
    $qry = 'INSERT INTO races (id_courier, id_region, departure_time, arrival_time)
			VALUES (:courierID, :regionID, :departureTime, addtime(:departureTime, (SELECT time FROM regions WHERE id_region = :regionID)))';
    $params = [
        ':courierID' => $courierID,
        ':regionID' => $regionID,
        ':departureTime' => $departureTime,
    ];
    $stmt = $pdo->prepare($qry);
    $stmt->execute($params);
    $raceID = $pdo->lastInsertId();
    $qry = "SELECT
			rc.id_race,
			CONCAT(c.first_name, ' ', c.last_name, ' ', c.middle_name) as courier_name,
			rg.region,
			rc.departure_time,
			rc.arrival_time
			FROM races  rc
			INNER JOIN regions rg ON rg.id_region = rc.id_region
			INNER JOIN couriers c ON c.id_courier = rc.id_courier
			WHERE id_race = ?";
    $stmt = $pdo->prepare($qry);
    $stmt->execute([$raceID]);
    return $stmt->fetch(PDO::FETCH_ASSOC);

};

function getRaces(&$pdo, $departure, $arrival)
{
    $qry = "SELECT
			CONCAT(c.first_name, ' ', c.last_name, ' ', c.middle_name) as courier_name,
			rg.region,
			rc.departure_time,
			rc.arrival_time
			FROM races rc
			INNER JOIN regions rg ON rg.id_region = rc.id_region
			INNER JOIN couriers c ON c.id_courier = rc.id_courier
			WHERE rc.departure_time >= ? AND rc.arrival_time <= ?";
    $stmt = $pdo->prepare($qry);
    $stmt->execute([$departure, $arrival]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
};

function listRacesInJson(&$pdo, $departure, $arrival)
{
    $res = getRaces($pdo, $departure, $arrival);
    if ($res == null || count($res) == 0) {
        echo json_encode([]);
        return;
    }
    echo json_encode($res);
}

function canCreateRace(&$pdo, $courierID, $departureTime, $arrivalTime)
{
    $qry = "SELECT
			COUNT(rc.id_race) AS count_of_races
			FROM races rc
			WHERE rc.id_courier = ? AND rc.departure_time <= ? AND rc.arrival_time > ?";
    $stmt = $pdo->prepare($qry);
    $stmt->execute([$courierID, $departureTime, $departureTime]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result["count_of_races"] >= 1) {
        return false;
    }

	$qry = "SELECT
			COUNT(rc.id_race) AS count_of_races
			FROM races rc
			WHERE rc.id_courier = ? AND rc.departure_time < ? AND rc.arrival_time >= ?";
    $stmt = $pdo->prepare($qry);
    $stmt->execute([$courierID, $arrivalTime, $arrivalTime]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result["count_of_races"] >= 1) {
        return false;
    }

    $qry = "SELECT
			COUNT(rc.id_race) AS count_of_races
			FROM races rc
			WHERE rc.id_courier = ? AND rc.departure_time > ? AND rc.arrival_time < ?";
    $stmt = $pdo->prepare($qry);
    $stmt->execute([$courierID, $departureTime, $arrivalTime]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result["count_of_races"] >= 1) {
        return false;
    }
    return true;
};
