<?php

namespace Configs\Database\SQL;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

class Desenvolvedora
{
	public static function getAll()
	{
		$sql = "";
		$sql .= "SELECT * ";
		$sql .= "FROM desenvolvedoras ";
		$sql .= "ORDER BY nome";

		return $sql;
	}

	public static function getById($id)
	{
		$sql = "";
		$sql .= "SELECT * FROM desenvolvedoras ";
		$sql .= "WHERE id = $id";

		return $sql;
	}

	public static function update($desenvolvedoraData)
	{
		$sql = "";
		$sql .= "UPDATE desenvolvedoras ";
		$sql .= "SET ";
		$sql .= "	nome = '" . $desenvolvedoraData['nome'] . "' ";
		$sql .= "WHERE id = " . $desenvolvedoraData['id'];

		return $sql;
	}

	public static function new($desenvolvedoraData)
	{
		$sql = "";
		$sql .= "INSERT INTO desenvolvedoras (nome)  VALUES ";
		$sql .= "	('" . $desenvolvedoraData['nome'] . "') ";

		return $sql;
	}
}
