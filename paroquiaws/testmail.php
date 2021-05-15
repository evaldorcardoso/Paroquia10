<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	$from = "suporte@paroquia10.com";
	//$to = "evaldorcardoso@outlook.com";
	//$subject = "Resetar senha de usuário do aplicativo Paróquia 10";
	//$message = "O correio do PHP funciona bem";
	$headers = "De:". $from;
	mail($to, $subject, $message, $headers);
	//echo "A mensagem de e-mail foi enviada.";
?>