<?php

namespace Components\Common;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

use  Configs\Constants\URL;

class Navbar
{
	public static function render($pageParams)
	{
		$authURL = URL::auth();
?>
		<nav class="navbar navbar-expand-lg bg-dark-subtle shadow sticky-top">
			<div class="container-fluid">
				<img src="<?= URL::root(); ?>/assets/images/logo.png" class="navbar-brand" height="32px" />
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav me-auto mb-2 mb-lg-0">
						<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="<?= $authURL ?>/">Home</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								Jogos
							</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="<?= $authURL ?>/jogos/avaliar.php">Avaliar</a></li>
								<li>
									<hr class="dropdown-divider">
								</li>
								<li><a class="dropdown-item" href="<?= $authURL ?>/jogos">Listar</a></li>
								<li><a class="dropdown-item" href="<?= $authURL ?>/jogos/form.php">Novo Jogo</a></li>
							</ul>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								Consoles
							</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="<?= $authURL ?>/consoles">Listar</a></li>
								<li><a class="dropdown-item" href="<?= $authURL ?>/consoles/form.php">Novo Console</a></li>
							</ul>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								Desenvolvedoras
							</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="<?= $authURL ?>/desenvolvedoras">Listar</a></li>
								<li><a class="dropdown-item" href="<?= $authURL ?>/desenvolvedoras/form.php">Nova Desenvolvedora</a></li>
							</ul>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								Gêneros
							</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="<?= $authURL ?>/generos">Listar</a></li>
								<li><a class="dropdown-item" href="<?= $authURL ?>/generos/form.php">Novo Gênero</a></li>
							</ul>
						</li>

					</ul>

					<a class="btn btn-outline-danger float-end" href="<?= $authURL ?>/logout.php">
						<i class="bi bi-box-arrow-right"></i>
						Sair
					</a>
				</div>
			</div>
		</nav>
		<div class="container-fluid">
			<div class="row">
				<?php if (isset($pageParams['subtitle'])) { ?>
					<div class="col-12">
						<h1 class="m-3"><?= $pageParams['subtitle']; ?></h1>
					</div>
				<?php } ?>
			</div>
		</div>
<?php
	}
}
?>