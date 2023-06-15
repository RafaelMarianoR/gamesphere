<?php

namespace Auth;

use Configs\Database\Connection;
use Configs\Database\SQL\Usuario;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

if (isset($_POST['user']) && isset($_POST['password'])) {

	$user = $_POST['user'];
	$pass = $_POST['password'];
	$conn = new Connection();

	$result =  mysqli_query($conn->connect(), Usuario::checkLogin($user, $pass));

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$_SESSION['auth'] = array(
				'id' => $row['id'],
				'nome' => $row['nome'],
				'usuario' => $row['usuario'],
				'email' => $row['email'],
			);
		}
		header("Location: /gamesphere/auth ");
	} else {
		$_SESSION['login-error'] = "Usuário ou senha incorreta!";
		header("Location: /gamesphere ");
	}

	$conn->close();
} else {
	$_SESSION['login-error'] = "Falha no login";
	$_SESSION['login-error'] = "Dados de login incompletos";
	header("Location: index.php");
}
