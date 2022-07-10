<?php
include_once 'database.php';

header('Content-Type: application/json; charset=utf-8');

function getCouriers(&$pdo) {
	$qry = "SELECT 
			c.id_courier,
            CONCAT(c.first_name, ' ', c.last_name, ' ', c.middle_name) as courier_name
			FROM couriers c";
	$stmt = $pdo->query($qry);
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result;
};
echo json_encode(getCouriers($dbh));
