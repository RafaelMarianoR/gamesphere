<?php

namespace Ajax;

use Configs\Database\Connection;
use Configs\Database\SQL\Jogo;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

$conn = Connection::conn();
$result;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$return = $_POST;
	switch ($_POST['action']) {
		case 'rem':
			$result = mysqli_query($conn, Jogo::deleteGeneroByGameIDAndGeneroID($_POST['gameID'], $_POST['genID']));
			if ($result) {
				$rows = mysqli_affected_rows($conn);
				if ($rows > 0) {
					$return['msg'] = "Remoção eftuada com sucesso. Linhas afetadas: " . $rows;
				} else {
					$return['msg'] = "Não há itens para remover";
				}
			} else {
				$return['msg'] = "Erro na Remoção: " . mysqli_error($conn);
			}
			break;
		case 'inc':
			$result = mysqli_query($conn, Jogo::insertGameGenero($_POST['gameID'], $_POST['genID']));
			if ($result) {
				$rows = mysqli_affected_rows($conn);
				if ($rows > 0) {
					$return['msg'] = "Inclusão eftuada com sucesso. Linhas afetadas: " . $rows;
				} else {
					$return['msg'] = "Item já incluso.";
				}
			} else {
				$return['msg'] = "Erro na Inclusão: " . mysqli_error($conn);
			}
			break;
	}
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$return = $_GET;
	switch ($_GET['action']) {
		case 'rem':
			$result = mysqli_query($conn, Jogo::deleteGeneroByGameIDAndGeneroID($_GET['gameID'], $_GET['genID']));
			if ($result) {
				$rows = mysqli_affected_rows($conn);
				if ($rows > 0) {
					$return['msg'] = "Remoção eftuada com sucesso. Linhas afetadas: " . $rows;
				} else {
					$return['msg'] = "Não há itens para remover";
				}
			} else {
				$return['msg'] = "Erro na Remoção: " . mysqli_error($conn);
			}
			break;
		case 'inc':
			$result = mysqli_query($conn, Jogo::insertGameGenero($_GET['gameID'], $_GET['genID']));
			if ($result) {
				$rows = mysqli_affected_rows($conn);
				if ($rows > 0) {
					$return['msg'] = "Inclusão eftuada com sucesso. Linhas afetadas: " . $rows;
				} else {
					$return['msg'] = "Item já incluso.";
				}
			} else {
				$return['msg'] = "Erro na Inclusão: " . mysqli_error($conn);
			}
			break;
	}
} else {
}

sleep(1);

$response = json_encode($return);

header('Content-Type: application/json');

echo $response;
