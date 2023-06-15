<?php

namespace Auth\Index;

use Configs\Database\Connection;
use Components\Common\Footer;
use Components\Common\Head;
use Components\Common\Navbar;
use Configs\Database\SQL\Jogo;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

try {
	if (!$_SESSION['auth']) {
		$_SESSION['login-error'] = "É necessário fazer o login!";
		header("Location: /gamesphere ");
		exit;
	} else {
		$pageParams['title'] = "Página Inicial - GameSphere";
		$pageParams['subtitle'] = "Jogos Avaliados";

		Head::renderHTML(pageParams: $pageParams, renderBodyTag: true);

		Navbar::render($pageParams);

		$conn = new Connection();

		$result =  mysqli_query($conn->connect(), Jogo::getAllAssessment()); ?>

		<?php if (mysqli_num_rows($result) > 0) { ?>
			<div class="container mt-2">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th scope="col">Nota</th>
							<th scope="col">Console</th>
							<th scope="col">Jogo<br /><span class="fs-7 text-secondary">Desenvolvedora</span></th>
							<th>Comentário</th>
						</tr>
					</thead>
					<tbody class="table-group-divider">
						<?php while ($row = mysqli_fetch_assoc($result)) { ?>
							<tr>
								<td scope="row"><?= $row['avaliacao']; ?></td>
								<td scope="row">
									<span class="badge rounded-pill text-bg-primary fs-6 my-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?= $row['console_nome']; ?>">
										<i class="<?= $row['console_icone']; ?>"></i>
									</span>
								</td>
								<td scope="row">
									<?= $row['titulo']; ?><br />
									<span class="fs-7 text-secondary"><?= $row['desenvolvedora']; ?></span>
								</td>
								<td scope="row"><?= $row['comentario']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		<?php } else { ?>
			<div class="container mt-5">
				<h1>Nenhuma Avaliação encontrada.</h1>
			</div>
		<?php } ?>

<?php Footer::renderHTML();
	}
} catch (\Throwable $th) {
	echo "ERRO";
	throw $th;
}
