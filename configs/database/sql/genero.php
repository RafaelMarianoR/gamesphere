<?php

namespace Configs\Database\SQL;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

class Genero
{
	public static function getAll()
	{
		$sql = "";
		$sql .= "SELECT * FROM generos";

		return $sql;
	}
	public static function getById($id)
	{
		$sql = "";
		$sql .= "SELECT * FROM generos ";
		$sql .= "WHERE id = $id";

		return $sql;
	}

	public static function update($generoData)
	{

		$sql = "";
		$sql .= "UPDATE generos ";
		$sql .= "SET ";
		$sql .= "	nome = '" . $generoData['nome'] . "' ";
		$sql .= "WHERE id = " . $generoData['id'];

		return $sql;
	}

	public static function new($generoData)
	{
		$sql = "";
		$sql .= "INSERT INTO generos (nome)  VALUES ";
		$sql .= "	('" . $generoData['nome'] . "') ";

		return $sql;
	}
}
