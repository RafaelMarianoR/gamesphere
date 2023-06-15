<?php
session_start();
unset($_SESSION["auth"]);
$_SESSION['notify'] = array(
	"delay" => 15000,
	"icon" => "bi bi-check-circle text-success",
	"title" => "Loggout",
	"text" => "Loggout efetuado com sucesso!",
);

header("Location: /gamesphere "); // REDIRECIONA DE VOLTA PARA A TELA DE LOGIN