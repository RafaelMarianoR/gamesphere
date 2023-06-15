<?php

namespace Configs\Database\SQL;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

class Console
{
	public static function getAll()
	{
		$sql = "";
		$sql .= "SELECT * ";
		$sql .= "FROM consoles ";
		// $sql .= "ORDER BY "

		return $sql;
	}

	public static function getById($id)
	{
		$sql = "";
		$sql .= "SELECT * FROM consoles ";
		$sql .= "WHERE id = $id";

		return $sql;
	}

	public static function update($consoleData)
	{
		$sql = "";
		$sql .= "UPDATE consoles ";
		$sql .= "SET ";
		$sql .= "	nome = '" . $consoleData['nome'] . "', ";
		$sql .= "	icone = '" . $consoleData['icone'] . "' ";
		$sql .= "WHERE id = " . $consoleData['id'];

		return $sql;
	}

	public static function new($consoleData)
	{
		$sql = "";
		$sql .= "INSERT INTO consoles (nome, icone) VALUES( ";
		$sql .= "	'" . $consoleData['nome'] . "', ";
		$sql .= "	'" . $consoleData['icone'] . "' ";
		$sql .= ")";

		return $sql;
	}
}
