<?php

namespace Auth\Jogos;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

use Components\Common\Footer;
use Components\Common\Head;
use Components\Common\Navbar;
use Configs\Database\Connection;
use Configs\Database\SQL\Console;
use Configs\Database\SQL\Desenvolvedora;
use Configs\Database\SQL\Genero;
use Configs\Database\SQL\Jogo;

$conn = new Connection();

$listaConsoles = array();
$listaDesenvolvedoras = array();
$listaGeneros = array();

$gameData = [];

if (!$_SESSION['auth']) {
	$_SESSION['login-errorr'] = "É necessário fazer o login!";
	header("Location: /gamesphere ");
	exit;
} else {
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$gameData = $_POST['jogo'];

		if (isset($gameData['id']))
			updateMainData($conn, $gameData);
		else
			insert($gameData);

		redirect();
	} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
		carregaConsoles($conn, $listaConsoles);
		carregaDesenvolvedoras($conn, $listaDesenvolvedoras);
		carregaGeneros($conn, $listaGeneros);
		$action;

		if (isset($_GET['action'])) {
			$action = $_GET['action'];
			switch ($action) {
				case 'edit':
					$pageParams['title'] = "Editar Jogo";
					$pageParams['subtitle'] = "Edição de Jogo";
					carregaJogo($_GET['id'], $conn, $gameData);
					break;
				case 'new':
					$pageParams['title'] = "Novo Jogo";
					$pageParams['subtitle'] = "Cadastro de novo Jogo";
					$gameData = array(
						'id	' => '',
						'id_desenvolvedora' => '',
						'titulo' => '',
						'descricao' => '',
						'data_lancamento' => '',
						'consoles' => array(),
						'generos' => array(),
					);
					break;
				case 'delete':
					// delete($_GET['id'], $conn);
					break;
				default:
					redirect();
					break;
			}
		} else {
			$action = 'new';
			$pageParams['title'] = "Novo Jogo";
			$pageParams['subtitle'] = "Cadastro de novo Jogo";
			$gameData = array(
				'id	' => '',
				'id_desenvolvedora' => '',
				'titulo' => '',
				'descricao' => '',
				'data_lancamento' => '',
				'consoles' => array(),
				'generos' => array(),
			);
		}
		Head::renderHTML($pageParams);

		Navbar::render($pageParams);

		renderForm($action, $gameData, $listaConsoles, $listaDesenvolvedoras, $listaGeneros);

		Footer::renderHTML($pageParams);
	} else { // REDIRECIONA SE NÃO HOUVER NADA PASSADO NA QUERY STRING OU POST
		redirect();
	}
}

function redirect()
{
	header("Location: /gamesphere/auth/jogos/ ");
	exit;
}

function carregaConsoles(&$conn, &$listaConsoles)
{
	$result = mysqli_query($conn->connect(), Console::getAll());

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$listaConsoles[$row['id']] = array(
				'id' => $row['id'],
				'nome' => $row['nome'],
				'icone' => $row['icone'],
			);
		}
	}

	$conn->close();
}

function carregaDesenvolvedoras(&$conn, &$listaDesenvolvedoras)
{
	$result = mysqli_query($conn->connect(), Desenvolvedora::getAll());

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$listaDesenvolvedoras[$row['id']] = array(
				'id' => $row['id'],
				'nome' => $row['nome'],
			);
		}
	}

	$conn->close();
}

function carregaGeneros(&$conn, &$listaGeneros)
{
	$result = mysqli_query($conn->connect(), Genero::getAll());

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$listaGeneros[$row['id']] = array(
				'id' => $row['id'],
				'nome' => $row['nome'],
			);
		}
	}

	$conn->close();
}

function carregaJogo($id, $conn, &$gameData)
{
	$result = mysqli_query($conn->connect(), Jogo::getFullDataById($id));

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {

			$consoleParts = [];
			$gameConsoles = [];

			if (!empty($row['consoles'])) {
				$consoles = explode("|", $row['consoles']);
				foreach ($consoles as $console) {
					$console = trim($console);
					$consoleParts = explode(",", $console);
					$gameConsoles[$consoleParts[0]] = array(
						'id' => $consoleParts[0],
						'icone' => $consoleParts[1],
						'nome' => $consoleParts[2],
					);
				}
			}

			$generosParts = [];
			$gameGeneros = [];

			if (!empty($row['generos'])) {
				$generos = explode("|", $row['generos']);
				foreach ($generos as $genero) {
					$genero = trim($genero);
					$generosParts = explode(",", $genero);
					$gameGeneros[$generosParts[0]] = array(
						'id' => $generosParts[0],
						'nome' => $generosParts[1],
					);
				}
			}


			$gameData = array(
				'id' => $row['id'],
				'titulo' => $row['titulo'],
				'descricao' => $row['descricao'],
				'data_lancamento' => $row['data_lancamento'],
				'id_desenvolvedora' => $row['id_desenvolvedora'],
				'desenvolvedora' => $row['desenvolvedora'],
				'consoles' => $gameConsoles,
				'generos' => $gameGeneros,
			);
		}
	} else {
		$conn->close();
		redirect();
	}

	$conn->close();
}

function insert(&$gameData)
{
	$conn = Connection::conn();

	if (mysqli_query($conn, Jogo::insertMainData($gameData))) {
		$lastID = mysqli_insert_id($conn);
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-check-circle text-success",
			"title" => "Jogo salvo",
			"text" => "Jogo foi salvo com sucesso.<br/>Termine o seu registro adicionando os Gêneros e Consoles.",
		);
		header("Location: /gamesphere/auth/jogos/form.php?action=edit&id=$lastID ");
		exit;
	} else {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-exclamation-circle text-danger",
			"title" => "Jogos",
			"text" => "Jogo não foi registrado.<br/>" . mysqli_error($conn),
		);
		$conn->close();
		redirect();
	}
}

function updateMainData(&$conn, &$gameData)
{
	if (mysqli_query($conn->connect(), Jogo::updateMainData($gameData))) {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-check-circle text-success",
			"title" => "Jogo atualizado",
			"text" => "Jogo atualizado com sucesso!",
		);
	} else {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-exclamation-circle text-danger",
			"title" => "Jogos",
			"text" => "Jogo não foi atualizado.<br/>" . mysqli_error($conn),
		);
		$conn->close();
		redirect();
	}
}

function loadConsolesByJogo(&$conn, &$gameID)
{
	$result = mysqli_query($conn->connect(), Jogo::getConsolesByGameID($gameID));

	$returnList = [];

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$returnList[$row['id']] = array(
				'id' => $row['id'],
				'id_jogo' => $row['id_jogo'],
				'id_console' => $row['id_console'],
			);
		}
		$conn->close();
		return $returnList;
	} else {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-exclamation-circle text-warning",
			"title" => "Atenção",
			"text" => "Nenhum Console encontrado!",
		);
		$conn->close();
		redirect();
	}
}

function loadGenerosByJogo(&$conn, &$gameID)
{
	$result = mysqli_query($conn->connect(), Jogo::getGenerosByGameID($gameID));

	$returnList = [];

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$returnList[$row['id']] = array(
				'id' => $row['id'],
				'id_jogo' => $row['id_jogo'],
				'id_genero' => $row['id_genero'],
			);
		}

		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-exclamation-circle text-warning",
			"title" => "Atenção",
			"text" => "Gênero encontrado!",
		);

		$conn->close();
		return $returnList;
	} else {
		$_SESSION['notify'] = array(
			"delay" => 15000,
			"icon" => "bi bi-exclamation-circle text-warning",
			"title" => "Atenção",
			"text" => "Nenhum Gênero encontrado!",
		);
		$conn->close();
		redirect();
	}
}

function renderForm($action, &$gameData, &$listaConsoles, &$listaDesenvolvedoras, &$listaGeneros)
{
?>

	<form action="form.php" method="POST" class="container mt-4">
		<?php if ($action == 'edit') { ?>
			<input type="hidden" name="jogo[id]" value="<?= $gameData['id']; ?>">
		<?php } ?>

		<div class="row mb-3">
			<div class="col-5">
				<div class="form-floating mb-3">
					<input type="text" class="form-control" id="titulo" placeholder="Título" name="jogo[titulo]" value="<?= $gameData['titulo']; ?>" required>
					<label for="titulo">Título</label>
				</div>
			</div>
			<div class="col-4">
				<div class="form-floating mb-3">
					<select class="form-select" id="desenvolvedora" name="jogo[id_desenvolvedora]" placeholder=" Título" required>
						<option value="" <?= (isset($gameData['id_desenvolvedora']) ? 'selected' : '') ?>>Selecione</option>
						<?php foreach ($listaDesenvolvedoras as $desenvolvedora) { ?>
							<option value="<?= $desenvolvedora['id']; ?>" <?= ($desenvolvedora['id'] == $gameData['id_desenvolvedora'] ? 'selected' : '') ?>>
								<?= $desenvolvedora['nome']; ?>
							</option>
						<?php } ?>
					</select>
					<label for="desenvolvedora">Desenvolvedora</label>
				</div>
			</div>
			<div class="col-3">
				<div class="form-floating mb-3">
					<input type="date" class="form-control" id="data_lancamento" placeholder="data_lancamento" name="jogo[data_lancamento]" value="<?= $gameData['data_lancamento']; ?>" required>
					<label for="data_lancamento">Data de Lançamento</label>
				</div>
			</div>
			<div class="col-6">
				<div class="row">
					<div class="col-12">
						<div class="form-floating mb-3">
							<textarea type="textarea" rows="4.5" class="form-control h-auto" id="descricao" placeholder="descricao" name="jogo[descricao]"><?= $gameData['descricao']; ?></textarea>
							<label for="descricao">Descricao</label>
						</div>
					</div>
					<div class="col-12">
						<fieldset class="continer p-3 rounded form-control">
							<legend class="fs-4">Consoles</legend>
							<div class="row d-flex justify-content-evenly">
								<?php if ($action == 'edit') { ?>
									<?php foreach ($listaConsoles as $console) { ?>
										<div class="col-6 my-3">
											<?php $checked = (isset($gameData['consoles'][$console['id']]) ? 'checked' : '') ?>
											<?php $operation = (isset($gameData['consoles'][$console['id']]) ? 'rem' : 'inc') ?>
											<input type="checkbox" class="btn-check" id="jogo[consoles][<?= $console['id']; ?>]" name="jogo[consoles][<?= $console['id']; ?>]" autocomplete="off" game="<?= $gameData['id']; ?>" console="<?= $console['id'] ?>" action="<?= $operation; ?>" <?= $checked ?> onchange="toggleConsole(this);">
											<label class="btn btn-outline-primary" for="jogo[consoles][<?= $console['id']; ?>]">
												<i class="<?= $console['icone'];  ?>"></i>
												<?= $console['nome'];  ?>
											</label>
										</div>
									<?php } ?>
								<?php } else { ?>
									<div class="position-relative my-5 p-3">
										<div class="position-absolute top-50 start-50 translate-middle text-center">
											<i class="bi bi-info-circle fs-3"></i>
											<p>Salve os dados do jogo para habilitar esta opção</p>
										</div>
									</div>
								<?php } ?>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
			<div class="col-6">
				<fieldset class="continer p-3 rounded form-control h-100">
					<legend class="fs-4">Gêneros</legend>
					<div class="row flex-wrap">

						<?php if ($action == 'edit') { ?>
							<?php foreach ($listaGeneros as $genero) { ?>
								<?php $checked = (isset($gameData['generos'][$genero['id']]) ? 'checked' : '') ?>
								<?php $operation = (isset($gameData['generos'][$genero['id']]) ? 'rem' : 'inc') ?>
								<div class="col badge rounded-pill m-2">
									<input type="checkbox" class="btn-check" id="jogo[generos][<?= $genero['id']; ?>]" name="jogo[generos][<?= $genero['id']; ?>]" game="<?= $gameData['id']; ?>" genero="<?= $genero['id'] ?>" action="<?= $operation; ?>" autocomplete="off" <?= $checked ?> onchange="toggletGenero(this);">
									<label class="btn btn-outline-primary" for="jogo[generos][<?= $genero['id']; ?>]">
										<?= $genero['nome']; ?>
									</label>
								</div>
							<?php } ?>
						<?php } else { ?>
							<div class="position-relative my-5 p-3">
								<div class="position-absolute top-50 start-50 translate-middle text-center">
									<i class="bi bi-info-circle fs-3"></i>
									<p>Salve os dados do jogo para habilitar esta opção</p>
								</div>
							</div>
						<?php } ?>
					</div>
				</fieldset>
			</div>
			<div class="col-12 my-3">
				<button type="submit" class="btn btn-primary float-end"><?= ($action == 'new' ? 'Incluir' : 'Salvar'); ?></button>
				<button class="btn btn-secondary float-end mx-3" onclick="history.back()">Voltar</button>
			</div>
		</div>
	</form>

<?php }
