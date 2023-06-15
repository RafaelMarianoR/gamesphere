<?php

use Components\Common\Footer;
use Components\Common\Head;
use Components\Common\Navbar;
use Configs\Database\Connection;
use Configs\Database\SQL\Desenvolvedora;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

if (!$_SESSION['auth']) {
	$_SESSION['login-error'] = "É necessário fazer o login!";
	header("Location: /gamesphere ");
	exit;
} else {
	$conn = new Connection();
	$desenvolvedoraData = [];

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		foreach ($_POST as $key => $value)
			$desenvolvedoraData[$key] = $value;

		if (isset($desenvolvedoraData['id']))
			atualizadesenvolvedora($conn, $desenvolvedoraData);
		else
			novaDesenvolvedora($conn, $desenvolvedoraData);
	} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {

		if (isset($_GET['action']) && $_GET['action'] == 'edit') {
			$pageParams['title'] = "Editar Desenvolvedora";
			$pageParams['subtitle'] = "Edição de Desenvolvedora";
			carregaDados($_GET['id'], $conn, $desenvolvedoraData);
		} else {
			$pageParams['title'] = "Novo Desenvolvedora";
			$pageParams['subtitle'] = "Cadastrar nova Desenvolvedora";
			$desenvolvedoraData = array(
				'nome' => '',
			);
		}

		Head::renderHTML($pageParams);

		Navbar::render($pageParams);

		renderForm($desenvolvedoraData);

		Footer::renderHTML($pageParams);
	} else {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-info-lg text-info",
			"title" => "Atenção",
			"text" => "Nenhum parâmetro repassado!",
		);
		header("Location: /gamesphere/auth/jogos/ ");
		exit;
	}
}

function carregaDados($id, &$conn, &$desenvolvedoraData)
{
	$result = mysqli_query($conn->connect(), desenvolvedora::getById($id));

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$desenvolvedoraData = array(
				'id' => $row['id'],
				'nome' => $row['nome'],
			);
		}
	} else {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-exclamation-circle text-warning",
			"title" => "Atenção",
			"text" => "Nenhuma desenvolvedora encontrada!",
		);
	}

	$conn->close();
}

function atualizadesenvolvedora(&$conn, &$desenvolvedoraData)
{
	if (mysqli_query($conn->connect(), desenvolvedora::update($desenvolvedoraData))) {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-check-circle text-success",
			"title" => "Desenvolvedoras",
			"text" => "Desenvolvedora foi atualizada!",
		);
	} else {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-exclamation-circle text-danger",
			"title" => "Desenvolvedoras",
			"text" => "Desenvolvedora não foi atualizada.<br/>" . mysqli_error($conn),
		);
	}

	$conn->close();

	header("Location: /gamesphere/auth/desenvolvedoras/ ");
	exit;
}

function novaDesenvolvedora(&$conn, &$desenvolvedoraData)
{
	if (mysqli_query($conn->connect(), Desenvolvedora::new($desenvolvedoraData))) {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-check-circle text-success",
			"title" => "Desenvolvedoras",
			"text" => "Desenvolvedora foi registrada!",
		);
	} else {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-exclamation-circle text-danger",
			"title" => "Desenvolvedoras",
			"text" => "Desenvolvedora não foi registrada.<br/>" . mysqli_error($conn),
		);
	}

	$conn->close();

	header("Location: /gamesphere/auth/desenvolvedoras/ ");
	exit;
}

function renderForm(&$desenvolvedoraData)
{ ?>
	<form action="form.php" method="POST" class="container mt-2">

		<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
			<input type="hidden" name="id" value="<?= $desenvolvedoraData['id']; ?>">
		<?php } ?>

		<div class="row mb-3">
			<div class=" col-12">
				<div class="form-floating mb-3">
					<input type="text" class="form-control" id="nome" placeholder="Nome" name="nome" value="<?= $desenvolvedoraData['nome']; ?>" required>
					<label for="nome">Nome</label>
				</div>
			</div>

			<div class="col-12">
				<button type="submit" class="btn btn-primary float-end"><?= (isset($desenvolvedoraData['id']) ? 'Salvar' : 'Incluir'); ?></button>
				<button class="btn btn-secondary float-end mx-3" onclick="history.back()">Voltar</button>
			</div>
		</div>
	</form>
<?php
}
