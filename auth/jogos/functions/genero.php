<?php
$generos = explode("|", $row['generos']);

foreach ($generos as $genero) {
	$genero = trim($genero); ?>
	<span class="badge rounded-pill text-bg-primary my-1"><?= $genero; ?></span>
<?php }
