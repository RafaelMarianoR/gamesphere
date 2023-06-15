<?php

namespace Auth\Jogos;

unset($_SESSION['loader']['Jogos']);
unset($_SESSION['loader']['Desenvolvedoras']);
$_SESSION['loader']['eu'] = 'Tirei';

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

use Configs\Constants\URL;
use Configs\Database\Connection;
use Configs\Database\SQL\Jogo;
use Components\Common\Head;
use Components\Common\Footer;
use Components\Common\Navbar;

if (!$_SESSION['auth']) {
	$_SESSION['login-error'] = "É necessário fazer o login!";
	header("Location: /gamesphere ");
	exit;
} else {
	$pageParams['title'] = "Listagem de Jogos - GameSphere";
	$pageParams['subtitle'] = "Lista de Jogos Cadastrados";

	Head::renderHTML(pageParams: $pageParams, renderBodyTag: true);

	Navbar::render($pageParams);

	$conn = new Connection();

	$result = mysqli_query($conn->connect(), Jogo::getAll());

	if (mysqli_num_rows($result) > 0) { ?>
		<div class="container-xl mt-2">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th scope="col">Título</th>
						<th scope="col">Desenvolvedora</th>
						<th scope="col">Console(s)</th>
						<th scope="col">Gêneros(s)</th>
						<th scope="col">Editar</th>
					</tr>
				</thead>
				<tbody class="table-group-divider">
					<?php while ($row = mysqli_fetch_assoc($result)) { ?>
						<tr>
							<td><?= $row['titulo']; ?></td>
							<td><?= $row['desenvolvedora']; ?></td>
							<td><?php require 'functions/console.php'; ?></td>
							<td><?php require 'functions/genero.php'; ?></td>
							<td class="text-center">
								<a href=" <?= URL::auth() . "/jogos/form.php?action=edit&id=" . $row['id']; ?>">
									<i class="bi bi-pencil link-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar"></i>
								</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="row">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<a href="<?= URL::auth() . "/jogos/form.php" ?>" class="btn btn-primary float-end">
								<i class="bi bi-plus-circle-fill fs-5"></i>
								Novo
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php } else {
		echo "Nenhum Console encontrado.";
	}

	$conn->close();
	echo "</body>";
	Footer::renderHtml();
}
