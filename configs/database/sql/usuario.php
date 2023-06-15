<?php

namespace Configs\Database\SQL;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

class Usuario
{
	public static function checkLogin($user, $pass)
	{
		return $sql = "SELECT * FROM usuarios WHERE usuario = '$user' AND senha = '$pass'";
	}
}
