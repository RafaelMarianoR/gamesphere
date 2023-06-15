<?php
session_start();
function autoLoader($className)
{
	$namespaceParts = explode('\\', $className);
	$item = end($namespaceParts);
	// $item = end(explode('\\', $className));
	$step = 0;

	$filePath = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/' . $className) . '.php';

	$_SESSION['loader'][$item]["PASSO: " . ++$step] = "[1][\$className][$className]";
	$_SESSION['loader'][$item]["PASSO: " . ++$step] = "[2][\$filePath ][$filePath]";

	if (file_exists($filePath)) {
		$_SESSION['loader'][$item]["PASSO: " . ++$step] = "[3][\$filePath>][" . file_exists($filePath) . "]";
		$_SESSION['loader'][$item]["PASSO: " . ++$step] = "[3][\$filePath ][$filePath]";
		require_once $filePath;
	} else {
		$_SESSION['loader'][$item]["PASSO: " . ++$step] = "[3][\$filePath>][" . file_exists($filePath) . "]";
	}
	// session_unset();
	// $_SESSION['auth'] = true;
}

spl_autoload_register('autoLoader');
