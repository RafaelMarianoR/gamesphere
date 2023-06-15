<?php

namespace homepage;

use Components\Common\Head;
use Components\Common\Footer;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

$pageParams['title'] = "GameSphere - Logins";
Head::renderHTML(pageParams: $pageParams, renderBodyTag: true); ?>

<div class="container">
	<div class="row justify-content-center align-items-center g-2">
		<div class="col-12">
			<h1 class="mt-5 text-center">Game Sphere</h1>
		</div>
		<div class="col-12">
			<h4 class="mt-0 text-center">Avaliações de Jogos</h4>
		</div>
		<div class="col-12">
			<img src="./assets/images/logo.png" class="rounded mx-auto d-block" width="128px" alt="">
		</div>
	</div>

	<form action="auth/login.php" method="POST" class="container mt-4">
		<?php if (isset($_SESSION['login-error'])) { ?>
			<div class="row mb-3">
				<div class="col-4 offset-4">
					<div class="alert alert-danger mt-4"><?= $_SESSION['login-error'] ?></div>
				</div>
			</div>
		<?php }	?>
		<div class="row mb-3">
			<div class="col-4 offset-4">
				<div class="form-floating mb-3 ">
					<input type="text" class="form-control" id="user" placeholder="seu usuário" name="user" value="">
					<label for="user">Usuário</label>
				</div>
			</div>
		</div>
		<div class="row mb-3">
			<div class="col-4 offset-4">
				<div class="form-floating mb-3">
					<input type="password" class="form-control" id="password" placeholder="sua senha" name="password" value="">
					<label for="password">Password</label>
				</div>
			</div>
		</div>
		<div class="row mb-3">
			<div class="col-4 offset-4">
				<button type="submit" class="btn btn-primary float-end">
					Entrar
					<i class="bi bi-box-arrow-in-right"></i>
				</button>
			</div>
		</div>

	</form>
</div>
<?php
Footer::renderHTML();
