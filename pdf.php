<?php

require_once('fpdf/fpdf.php');

$chatId = $_POST["chatId"];
$chatName = $_POST["chatName"];

$sql = "SELECT COUNT(*) AS sum FROM messages WHERE chat_id = :chat_id";
$count = pdoFetch($sql, [
	"chat_id" => $_POST['chatId']
]);
$key = $chatName.$count['sum'];
$messages = $memcache->get($key);

if (!$messages) {
	$sql = "SELECT message FROM messages WHERE chat_id = :chat_id";
	$messages = pdoFetchAll($sql, [
		"chat_id" => $_POST['chatId']
	]);
	$memcache->set($key, $messages, false, 86400);
}

$pdf= new FPDF();

$pdf->SetAuthor('Admin');
$pdf->SetTitle($chatName);
$pdf->SetFont('Helvetica','B',20);
$pdf->SetTextColor(50,60,100);
$pdf->AddPage('P');
$pdf->SetDisplayMode('real','default');
$pdf->SetXY(50,20);
$pdf->SetDrawColor(50,60,100);
$pdf->Cell(100,10,$chatName,0,0,'C',0);

$pdf->SetXY(10,50);
$pdf->SetFontSize(10);

foreach($messages as $message){
    $pdf->Cell(50,5,$message["message"]);
    $pdf->Ln(5);
}

$pdf->Output('example1.pdf','I');
