<?php

namespace Components\Common;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

use  Configs\Constants\URL;

class Head
{
	public static function renderEcho($pageParams)
	{
		$url = URL::root();
		$html = "";
		$html .= "<!DOCTYPE html>";
		$html .= "<html lang='pt-BR' class='h-100'>";
		$html .= "<head origin='" . __METHOD__ . "'>";
		$html .= "	<meta charset='UTF-8'>";
		$html .= "	<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
		$html .= "	<title>" . $pageParams['title'] ?? 'GAMESPHERE' . "</title>";
		$html .= "	<link rel='stylesheet' href='" . $url . "/assets/css/styles.css'>";
		$html .= "	<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>";
		$html .= "	<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css'>";
		$html .= "</head>";
		$html .= "<body>";

		echo $html;
	}

	public static function renderHTML($pageParams, $renderBodyTag = true, $renderHeaderTag = false)
	{ ?>
		<!DOCTYPE html>
		<html lang='pt-BR'>

		<head origin="<?= __FUNCTION__ ?>()">
			<meta charset='UTF-8'>
			<meta name='viewport' content='width=device-width, initial-scale=1.0'>
			<title><?= $pageParams['title'] ?? 'GAMESPHERE'; ?></title>
			<?php if (isset($pageParams['links']['css'])) { ?>
				<?php foreach ($pageParams['links']['css'] as $key => $value) { ?>
					<link id="<?= $key; ?>" rel='stylesheet' href='<?= $value; ?>'>
				<?php } ?>
			<?php } ?>
			<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>
			<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css'>
			<link rel='stylesheet' href='<?= URL::root(); ?>/assets/css/styles.css?version=<?= rand(0, 150) . "." . rand(0, 100) . "." . rand(0, 15) ?>'>
		</head>
		<?php if ($renderBodyTag) { ?>

			<body origin="<?= __FUNCTION__ ?>()" id="body" class="position-relative" style="padding-bottom: 5rem !important">
				<?php if ($renderHeaderTag) { ?>
					<header orign=" <?= __FUNCTION__ ?>">
					</header>
	<?php
				}
			}
		}
	}
