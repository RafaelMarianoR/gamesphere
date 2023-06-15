<?php

use Components\Common\Footer;
use Components\Common\Head;
use Components\Common\Navbar;
use Configs\Database\Connection;
use Configs\Database\SQL\Console;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

if (!$_SESSION['auth']) {
	$_SESSION['login-error'] = "É necessário fazer o login!";
	header("Location: /gamesphere ");
	exit;
} else {

	$conn = new Connection();
	$consoleData = [];

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		foreach ($_POST as $key => $value)
			$consoleData[$key] = $value;

		if (isset($consoleData['id']))
			atualizaConsole($conn, $consoleData);
		else
			novoConsole($conn, $consoleData);
	} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {

		if (isset($_GET['action'])) {
			if ($_GET['action'] == 'edit') {
				$pageParams['title'] = "Editar Console";
				$pageParams['subtitle'] = "Edição de Console";
				carregaDados($_GET['id'], $conn, $consoleData);
			}
		} else {
			$pageParams['title'] = "Novo Console";
			$pageParams['subtitle'] = "Cadastrar novo Console";
			$consoleData = array(
				'nome' => '',
				'icone' => '',
			);
		}

		Head::renderHTML($pageParams);

		Navbar::render($pageParams);

		renderForm($consoleData);

		Footer::renderHTML($pageParams);
	} else { // REDIRECIONA SE NÃO HOUVER NADA PASSADO NA QUERY STRING OU POST
		header("Location: /gamesphere/auth/jogos/ ");
		exit;
	}
}

function carregaDados($id, &$conn, &$consoleData)
{
	$result = mysqli_query($conn->connect(), Console::getById($id));

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$consoleData = array(
				'id' => $row['id'],
				'nome' => $row['nome'],
				'icone' => $row['icone'],
			);
		}
	} else {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-exclamation-circle text-warning",
			"title" => "Consoles",
			"text" => "Nenhum console encontrado!",
		);
	}

	$conn->close();
}

function atualizaConsole(&$conn, &$consoleData)
{
	if (mysqli_query($conn->connect(), Console::update($consoleData))) {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-check-circle text-success",
			"title" => "Console",
			"text" => "Console atualizado com sucesso!",
		);
	} else {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-exclamation-circle text-warning",
			"title" => "Consoles",
			"text" => "Erro ao atualizar console:</br>" . mysqli_error($conn),
		);
	}
	$conn->close();

	header("Location: /gamesphere/auth/consoles/ ");
	exit;
}

function novoConsole(&$conn, &$consoleData)
{
	$_SESSION['sql'] = Console::new($consoleData);

	if (mysqli_query($conn->connect(), Console::new($consoleData))) {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-check-circle text-success",
			"title" => "Consoles",
			"text" => "Console salvo com sucesso!",
		);
	} else {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-exclamation-circle text-warning",
			"title" => "Consoles",
			"text" => "Erro ao salvar console:</br>" . mysqli_error($conn),
		);
	}

	$conn->close();

	header("Location: /gamesphere/auth/consoles/ ");
	exit;
}

function renderForm(&$consoleData)
{
?>
	<form action="form.php" method="POST" class="container mt-2">

		<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
			<input type="hidden" name="id" value="<?= $consoleData['id']; ?>">
		<?php } ?>

		<div class="row mb-3">
			<div class="col-6">
				<div class="form-floating mb-3">
					<input type="text" class="form-control" id="nome" placeholder="Título" name="nome" value="<?= $consoleData['nome']; ?>" required>
					<label for="nome">Título</label>
				</div>
			</div>
			<div class=" col-6">
				<div class="form-floating mb-3">
					<input type="text" class="form-control" id="icone" placeholder="Ícone" name="icone" value="<?= $consoleData['icone']; ?>" required>
					<label for="icone">Ícone</label>
				</div>
			</div>
			<div class="col-12">
				<button type="submit" class="btn btn-primary float-end"><?= (isset($consoleData['id']) ? 'Salvar' : 'Incluir'); ?></button>
				<button class="btn btn-secondary float-end mx-3" onclick="history.back()">Voltar</button>
			</div>
		</div>
	</form>
<?php
}
