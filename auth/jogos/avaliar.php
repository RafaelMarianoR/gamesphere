<?php

namespace Auth\Jogos\Avaliar;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

use Components\Common\Footer;
use Components\Common\Head;
use Components\Common\Navbar;
use Configs\Database\Connection;
use Configs\Database\SQL\Console;
use Configs\Database\SQL\Jogo;

$listaConsoles = array();
$gameData = [];

if (!$_SESSION['auth']) {
	$_SESSION['login-errorr'] = "É necessário fazer o login!";
	header("Location: /gamesphere "); // REDIRECIONA DE VOLTA PARA A TELA DE LOGIN
	exit;
} else {
	$pageParams['title'] = "Nova Avaliação - GameSphere";
	$pageParams['subtitle'] = "Nova avaliação de Jogo";

	$conn = new Connection();

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$_POST['avaliacao']['comentario'] = nl2br($_POST['avaliacao']['comentario']);
		// echo nl2br($_POST['avaliacao']['comentario']);
		$result = mysqli_query($conn->connect(), Jogo::insertAssessment($_POST['avaliacao']));
		$conn->close();
		redirect();
	} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {

		$result = mysqli_query($conn->connect(), Jogo::getToAssess());

		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				$gameData[$row['id']] = array(
					'id' => $row['id'],
					'titulo' => $row['titulo'],
					'desenvolvedora' => $row['desenvolvedora'],
					'consoles' => $row['consoles'],
				);
			}
		} else {
			$_SESSION['notify'] =  "Nenhum Jogo encontrado";
		}

		$conn->close();

		Head::renderHTML($pageParams);

		Navbar::render($pageParams);

		carregaConsoles($conn, $listaConsoles);

		renderForm($gameData, $listaConsoles);

		Footer::renderHTML();
	}
}
function redirect()
{
	header("Location: /gamesphere/auth/ ");
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

function renderForm(&$gameData, &$listaConsoles)
{ ?>
	<form action="avaliar.php" method="POST" class="container-xl mt-2">
		<input type="hidden" name="avaliacao[id_usuario]" value="<?= $_SESSION['auth']['id']; ?>">
		<div class="row mb-3">
			<div class="col-sm-12 col-md-6 px-1 py-2">
				<div class="row">
					<div class="col-md-8 col-9">
						<div class="form-floating mb-3">
							<select class="form-select" id="titulo" name="avaliacao[id_jogo]" placeholder=" Título" required onchange="toggleGame(this);">
								<option value="" consoles="0" selected>Selecione</option>
								<?php foreach ($gameData as $game) { ?>
									<option value="<?= $game['id']; ?>" consoles="<?= $game['consoles']; ?>">
										<?= $game['titulo']; ?>
									</option>
								<?php } ?>
							</select>
							<label for="titulo">Título</label>
						</div>
					</div>
					<div class="col-md-4 col-3">
						<div class="form-floating mb-3">
							<input type="number" class="form-control" id="nota" placeholder="Nota" name="avaliacao[nota]" min="0" max="5" step="0.1" required>
							<label for="nota">Nota</label>
						</div>
					</div>
					<div class="col-12">
						<fieldset class="continer p-3 rounded form-control position-relative">
							<legend class="fs-6">Consoles</legend>
							<div class="row d-flex justify-content-evenly">
								<div class="btn-group flex-wrap justify-content-center align-middle" id="consolesGroup" role="group" aria-label="Grupo de console">
									<?php foreach ($listaConsoles as $console) { ?>
										<div class="col-6 col-md-6 col-lg-4 mt-2 px-3 form-check align-middle">
											<?php $checked = (isset($gameData['consoles'][$console['id']]) ? 'checked' : '') ?>
											<input type="radio" class="btn-check" id="avaliacao[console][<?= $console['id']; ?>]" value="<?= $console['id']; ?>" name="avaliacao[id_console]" autocomplete="off" <?= $checked ?> disabled required>
											<label class="btn btn-outline-primary w-100 h-100 my-auto" for="avaliacao[console][<?= $console['id']; ?>]">
												<i class="<?= $console['icone']; ?>"></i>
												<br /><?= $console['nome']; ?>
											</label>
										</div>
									<?php } ?>
								</div>
							</div>
							<div id="noConsoles" style="display: none">
								<div class="position-absolute top-50 start-50 translate-middle text-center w-100 px-3">
									<i class="bi bi-exclamation-octagon text-warning fs-1"></i><br />
									<p>O jogo não tem <strong>Consoles</strong> associados.</p>
									<p>Faça <a id="editLink" href="#" class="link-info">edição do jogo</a> para atribuir consoles e retorne para avaliá-lo</p>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
			</div>

			<div class="col-sm-12 col-md-6 px-1 py-2">
				<div class="row h-100">
					<div class="col-12 h-100">
						<div class="form-floating mb-3 h-100">
							<textarea type="textarea" class="form-control h-100" id="comentario" placeholder="comentario" name="avaliacao[comentario]" required></textarea>
							<label for="comentario">Comentário</label>
						</div>
					</div>
				</div>
			</div>

			<div class="col-12 mt-3 ">
				<button type="submit" id="btnSubmit" class="btn btn-primary float-end">Salvar</button>
				<div class="btn btn-secondary float-end mx-3" onclick="history.back()">Voltar</div>
			</div>
		</div>
	</form>
	<script>
		const noConsoles = document.getElementById('noConsoles');
		const btnSubmit = document.getElementById('btnSubmit');
		const radioButtons = document.getElementById('consolesGroup').querySelectorAll('input[type="radio"]');
		const editLink = document.getElementById('editLink');

		function toggleGame(parentElem) {
			var elem = parentElem.options[parentElem.selectedIndex];

			for (var i = 0; i < radioButtons.length; i++) {
				var radioButton = radioButtons[i];
				radioButton.disabled = true;
				radioButton.checked = false;
			}


			var consoles = elem.getAttribute('consoles').split(',');
			if (consoles.length == 1 && consoles[0] == 0) {
				if (elem.value != '') {
					noConsoles.style.display = 'initial';
					btnSubmit.style.display = 'none';
					editLink.href = "form.php?action=edit&id=" + elem.value;
				}
			} else {
				noConsoles.style.display = 'none';
				btnSubmit.style.display = 'initial';

				for (var i = 0; i < consoles.length; i++) {
					var id = consoles[i];
					var element = document.getElementById('avaliacao[console][' + id + ']');

					if (element) {
						element.disabled = false;
					}
				}
			}
		}
	</script>
<?php

}
