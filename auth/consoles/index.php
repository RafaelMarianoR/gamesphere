<?php

namespace Auth\Consoles;

use Configs\Constants\URL;
use Components\Common\Footer;
use Components\Common\Head;
use Components\Common\Navbar;
use Configs\Database\Connection;
use Configs\Database\SQL\Console;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

if (!$_SESSION['auth']) {
	$_SESSION['login-errorr'] = "É necessário fazer login!";
	header("Location: /gamesphere ");
	exit;
} else {
	$pageParams['title'] = "Listagem de Consoles - GameSphere";
	$pageParams['subtitle'] = "Lista de Consoles cadastrados";

	Head::renderHTML($pageParams);

	Navbar::render($pageParams);

	$conn = new Connection();

	$result = mysqli_query($conn->connect(), Console::getAll());

	if (mysqli_num_rows($result) > 0) { ?>
		<div class="container mt-2">
			<table class="table table-striped table-hover shadow">
				<thead>
					<tr>
						<th scope="col" class="text-center">#</th>
						<th scope="col" class="text-center">Icone</th>
						<th scope="col">Nome</th>
						<th scope="col">Editar</th>
					</tr>
				</thead>
				<tbody class="table-group-divider">
					<?php while ($row = mysqli_fetch_assoc($result)) { ?>
						<tr>
							<th scope="row" class="text-center"><?= $row['id']; ?></th>
							<td class="text-center fs-5">
								<i class="<?= $row['icone']; ?>"></i>
							</td>
							<td><?= $row['nome']; ?></td>
							<td>
								<a href="<?= URL::auth() . "/consoles/form.php?action=edit&id=" . $row['id']; ?>">
									<i class="bi bi-pencil link-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar"></i>
								</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<a href="<?= URL::auth() . "/consoles/form.php" ?>" class="btn btn-primary float-end">
						<i class="bi bi-plus-circle-fill fs-5"></i>
						Novo
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
