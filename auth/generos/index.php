<?php

namespace Auth\Generos;

use Components\Common\Footer;
use Components\Common\Head;
use Components\Common\Navbar;
use Configs\Constants\URL;
use Configs\Database\Connection;
use Configs\Database\SQL\Genero;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

if (!$_SESSION['auth']) {
	$_SESSION['login-error'] = "É necessário fazer o login!";
	header("Location: /gamesphere ");
	exit;
} else {
	$pageParams['title'] = "Listagem de Gêneros - GameSphere";
	$pageParams['subtitle'] = "Lista de Gêneros cadastrados";

	Head::renderHTML($pageParams);

	Navbar::render($pageParams);

	$conn = new Connection();

	$result = mysqli_query($conn->connect(), Genero::getAll());

	if (mysqli_num_rows($result) > 0) { ?>
		<div class="container mt-2">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th scope="col" class="text-center">#</th>
						<th scope="col">Nome</th>
						<th scope="col">Editar</th>
					</tr>
				</thead>
				<tbody class="table-group-divider container">
					<?php while ($row = mysqli_fetch_assoc($result)) { ?>
						<tr>
							<th scope="row" class="text-center"><?= $row['id']; ?></th>
							<td><?= $row['nome']; ?></td>
							<td>
								<a href="<?= URL::auth() . "/generos/form.php?action=edit&id=" . $row['id']; ?>">
									<i class="bi bi-pencil link-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar"></i>
								</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="container mb-6">
			<div class="row">
				<div class="col-12">
					<a href="<?= URL::auth() . "/generos/form.php" ?>" class="btn btn-primary float-end">
						<i class="bi bi-plus-circle-fill fs-5"></i>
						Novo
					</a>
				</div>
		<?php
		$conn->close();
	} else {
		echo "Nenhum Gênero encontrado.";
	}

	Footer::renderHTML();
}
