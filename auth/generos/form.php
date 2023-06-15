<?php

use Components\Common\Footer;
use Components\Common\Head;
use Components\Common\Navbar;
use Configs\Database\Connection;
use Configs\Database\SQL\Genero;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

if (!$_SESSION['auth']) {
	$_SESSION['login-error'] = "É necessário fazer o login!";
	header("Location: /gamesphere ");
	exit;
} else {
	$conn = new Connection();
	$generoData = [];

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		foreach ($_POST as $key => $value)
			$generoData[$key] = $value;

		if (isset($generoData['id']))
			atualizagenero($conn, $generoData);
		else
			novoGenero($conn, $generoData);
	} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {

		if (isset($_GET['action']) && $_GET['action'] == 'edit') {
			$pageParams['title'] = "Editar Gênero";
			$pageParams['subtitle'] = "Edição de Gênero";
			carregaDados($_GET['id'], $conn, $generoData);
		} else {
			$pageParams['title'] = "Novo Gênero";
			$pageParams['subtitle'] = "Cadastrar novo Gênero";
			$generoData = array(
				'nome' => '',
			);
		}

		Head::renderHTML($pageParams);

		Navbar::render($pageParams);

		renderForm($generoData);

		Footer::renderHTML($pageParams);
	} else {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-exclamation-circle text-danger",
			"title" => "Gêneros",
			"text" => "Nenhum parâmetro foi repassado.",
		);
		header("Location: /gamesphere/auth/jogos/ ");
		exit;
	}
}


function carregaDados($id, &$conn, &$generoData)
{
	$result = mysqli_query($conn->connect(), genero::getById($id));

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$generoData = array(
				'id' => $row['id'],
				'nome' => $row['nome'],
			);
		}
	}

	$conn->close();
}

function atualizagenero(&$conn, &$generoData)
{
	if (mysqli_query($conn->connect(), genero::update($generoData))) {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-check-circle text-success",
			"title" => "Gêneros",
			"text" => "Gênero foi atualizado!",
		);
	} else {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-exclamation-circle text-danger",
			"title" => "Gêneros",
			"text" => "Gênero não foi atualizado.<br/>" . mysqli_error($conn),
		);
	}

	$conn->close();

	header("Location: /gamesphere/auth/generos/ ");
	exit;
}

function novoGenero(&$conn, &$generoData)
{
	$_SESSION['sql'] = Genero::new($generoData);
	if (mysqli_query($conn->connect(), Genero::new($generoData))) {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-check-circle text-success",
			"title" => "Gêneros",
			"text" => "Gênero foi registrada!",
		);
	} else {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-exclamation-circle text-danger",
			"title" => "Gêneros",
			"text" => "Gênero não foi registrada.<br/>" . mysqli_error($conn),
		);
	}

	$conn->close();

	header("Location: /gamesphere/auth/generos/ ");
	exit;
}

function renderForm(&$generoData)
{ ?>
	<form action="form.php" method="POST" class="container mt-2">

		<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
			<input type="hidden" name="id" value="<?= $generoData['id']; ?>">
		<?php } ?>

		<div class="row mb-3">
			<div class=" col-12">
				<div class="form-floating mb-3">
					<input type="text" class="form-control" id="nome" placeholder="Nome" name="nome" value="<?= $generoData['nome']; ?>" required>
					<label for="nome">Nome</label>
				</div>
			</div>

			<div class="col-12">
				<button type="submit" class="btn btn-primary float-end"><?= (isset($generoData['id']) ? 'Salvar' : 'Incluir'); ?></button>
				<button class="btn btn-secondary float-end mx-3" onclick="history.back()">Voltar</button>

			</div>
		</div>
	</form>
<?php
}
