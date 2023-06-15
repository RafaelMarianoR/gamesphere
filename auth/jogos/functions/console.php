<?php
$consoles = explode("|", $row['consoles']);

foreach ($consoles as $console) {
	$console = trim($console);
	$consoleParts = explode(",", $console);
	if (isset($consoleParts[1])) { ?>
		<span class="badge rounded-pill text-bg-primary fs-6 my-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?= $consoleParts[1]; ?>">
			<i class="<?= $consoleParts[0]; ?>"></i>
		</span>
<?php
	}
}
