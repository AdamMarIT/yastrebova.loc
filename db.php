<?php 
$dns = "mysql:host=localhost;port=3306;dbname=chat;charset=utf8";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

$pdo = new PDO($dns, 'root', 'root', $options);

function pdoFetchAll($pdo, $mysql, $params = null) {
	$smtp = $pdo->prepare($mysql);
	$smtp->execute($params);

	return $smtp->fetchAll();
}

function pdoFetch($pdo, $mysql, $params = null) {
	$smtp = $pdo->prepare($mysql);
	$smtp->execute($params);

	return $smtp->fetch();
}

function pdoInsert($pdo, $mysql, $params = null) {
	$smtp = $pdo->prepare($mysql);
	$smtp->execute($params);
}