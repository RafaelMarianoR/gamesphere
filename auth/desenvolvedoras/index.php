<?php

namespace Auth\Desenvolvedoras;

use Components\Common\Footer;
use Components\Common\Head;
use Components\Common\Navbar;
use Configs\Constants\URL;
use Configs\Database\Connection;
use Configs\Database\SQL\Desenvolvedora;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

if (!$_SESSION['auth']) {
	$_SESSION['login-error'] = "É necessário fazer o login!";
	header("Location: /gamesphere ");
	exit;
} else {
	$pageParams['title'] = "Listagem de Desenvolvedora - GameSphere";
	$pageParams['subtitle'] = "Lista de Desenvolvedora cadastradas";

	Head::renderHTML($pageParams);

	Navbar::render($pageParams);

	$conn = new Connection();

	$result = mysqli_query($conn->connect(), Desenvolvedora::getAll());

	if (mysqli_num_rows($result) > 0) { ?>
		<div class="container mt-2">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th scope="col" class="text-center">#</th>
						<th scope="col">Nome</th>
						<th scope="col" colspan="2">Editar</th>
					</tr>
				</thead>
				<tbody class="table-group-divider">
					<?php while ($row = mysqli_fetch_assoc($result)) { ?>
						<tr>
							<th scope="row" class="text-center"><?= $row['id']; ?></th>
							<td><?= $row['nome']; ?></td>
							<td>
								<a href="<?= URL::auth() . "/desenvolvedoras/form.php?action=edit&id=" . $row['id']; ?>">
									<i class="bi bi-pencil link-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar"></i>
								</a>
							</td>
							<td>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="container mb-6">
			<div class="row">
				<div class="col-12">
					<a href="<?= URL::auth() . "/desenvolvedoras/form.php" ?>" class="btn btn-primary float-end">
						<i class="bi bi-plus-circle-fill fs-5"></i>
						Nova
					</a>
				</div>
			</div>
		</div>
<?php
		$conn->close();
	} else {
		echo "Nenhum Console encontrado.";
	}

	Footer::renderHTML();
}
