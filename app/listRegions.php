<?php
include_once 'database.php';

header('Content-Type: application/json; charset=utf-8');

function getRegions(&$pdo) {
	$qry = "SELECT 
			id_region,
            region,
			time
			FROM regions";
	$stmt = $pdo->query($qry);
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $result;
};
echo json_encode(getRegions($dbh));
