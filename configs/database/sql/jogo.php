<?php

namespace Configs\Database\SQL;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

class Jogo
{
	public static function getAll()
	{
		$sql = "";
		$sql .= "SELECT ";
		$sql .= "	j.id AS id, ";
		$sql .= "	j.titulo AS titulo, ";
		$sql .= "	d.nome AS desenvolvedora, ";
		$sql .= "	GROUP_CONCAT(DISTINCT CONCAT(c.icone, ',', c.nome) SEPARATOR '|') AS consoles, ";
		$sql .= "	GROUP_CONCAT(DISTINCT g.nome SEPARATOR '|') AS generos ";
		$sql .= "FROM jogos j ";
		$sql .= "LEFT JOIN desenvolvedoras d ON j.id_desenvolvedora = d.id ";
		$sql .= "LEFT JOIN jogos_consoles jc ON j.id = jc.id_jogo ";
		$sql .= "LEFT JOIN consoles c ON jc.id_console = c.id ";
		$sql .= "LEFT JOIN jogos_generos jg ON j.id = jg.id_jogo ";
		$sql .= "LEFT JOIN generos g ON jg.id_genero = g.id ";
		$sql .= "GROUP BY j.id ";
		$sql .= "ORDER BY j.titulo; ";

		return $sql;
	}

	public static function getFullDataById($id)
	{
		$sql = "";
		$sql .= "SELECT ";
		$sql .= "	j.id, ";
		$sql .= "	j.titulo, ";
		$sql .= "	j.descricao, ";
		$sql .= "	j.data_lancamento, ";
		$sql .= "	d.id AS id_desenvolvedora, ";
		$sql .= "	d.nome AS desenvolvedora, ";
		$sql .= "	COALESCE(GROUP_CONCAT(DISTINCT CONCAT(c.id, ',', c.icone, ',', c.nome) SEPARATOR '|'), NULL) AS consoles, ";
		$sql .= "	COALESCE(GROUP_CONCAT(DISTINCT CONCAT(g.id, ',', g.nome) SEPARATOR '|'), NULL) AS generos ";
		$sql .= "FROM jogos j ";
		$sql .= "LEFT JOIN desenvolvedoras d ON j.id_desenvolvedora = d.id ";
		$sql .= "LEFT JOIN jogos_consoles jc ON j.id = jc.id_jogo ";
		$sql .= "LEFT JOIN consoles c ON jc.id_console = c.id ";
		$sql .= "LEFT JOIN jogos_generos jg ON j.id = jg.id_jogo ";
		$sql .= "LEFT JOIN generos g ON jg.id_genero = g.id ";
		$sql .= "WHERE j.id = $id ";
		$sql .= "GROUP BY j.id; ";

		return $sql;
	}

	public static function getToAssess()
	{
		$sql = "";
		$sql .= "SELECT ";
		$sql .= "	j.id, ";
		$sql .= "	j.titulo, ";
		$sql .= "	j.descricao, ";
		$sql .= "	j.data_lancamento, ";
		$sql .= "	d.id AS id_desenvolvedora, ";
		$sql .= "	d.nome AS desenvolvedora, ";
		$sql .= "	COALESCE(GROUP_CONCAT(DISTINCT c.id SEPARATOR ','), 0) AS consoles ";
		$sql .= "FROM jogos j ";
		$sql .= "LEFT JOIN desenvolvedoras d ON j.id_desenvolvedora = d.id ";
		$sql .= "LEFT JOIN jogos_consoles jc ON j.id = jc.id_jogo ";
		$sql .= "LEFT JOIN consoles c ON jc.id_console = c.id ";
		$sql .= "GROUP BY j.id ";
		$sql .= "ORDER BY j.titulo; ";

		return $sql;
	}

	public static function updateMainData($gameData)
	{
		$sql = "";
		$sql .= "UPDATE jogos ";
		$sql .= "SET ";
		$sql .= "titulo = '" . $gameData['titulo'] . "', ";
		$sql .= "id_desenvolvedora = '" . $gameData['id_desenvolvedora'] . "', ";
		$sql .= "descricao = '" . $gameData['descricao'] . "', ";
		$sql .= "data_lancamento = '" . $gameData['data_lancamento'] . "' ";
		$sql .= "WHERE id = " . $gameData['id'];

		return $sql;
	}

	public static function insertMainData($gameData)
	{
		$sql = "";
		$sql .= "INSERT INTO jogos ";
		$sql .= "(id_desenvolvedora, titulo, descricao, data_lancamento) ";
		$sql .= "VALUES ( ";
		$sql .= $gameData['id_desenvolvedora'] . ", ";
		$sql .= "'" . $gameData['titulo'] . "',";
		$sql .= "'" . $gameData['descricao'] . "',";
		$sql .= "'" . $gameData['data_lancamento'] . "')";

		return $sql;
	}

	public static function getConsolesByGameID($gameID)
	{
		$sql = "";
		$sql .= "SELECT * ";
		$sql .= "FROM jogos_consoles ";
		$sql .= "WHERE id_jogo = $gameID";

		return $sql;
	}

	public static function getGenerosByGameID($gameID)
	{
		$sql = "";
		$sql .= "SELECT * ";
		$sql .= "FROM jogos_generos ";
		$sql .= "WHERE id_jogo = $gameID";

		return $sql;
	}

	public static function deleteConsoleByGameIDAndConsoleID($gameID, $consoleID)
	{
		$sql = "";
		$sql .= "DELETE ";
		$sql .= "FROM jogos_consoles ";
		$sql .= "WHERE id_jogo = $gameID ";
		$sql .= "	AND  id_console = $consoleID ";

		return $sql;
	}

	public static function deleteGeneroByGameIDAndGeneroID($gameID, $generoID)
	{
		$sql = "";
		$sql .= "DELETE ";
		$sql .= "FROM jogos_generos ";
		$sql .= "WHERE id_jogo = $gameID ";
		$sql .= "AND  id_genero = $generoID ";

		return $sql;
	}

	public static function insertGameConsole($gameID, $consoleID)
	{
		$sql = "";
		$sql .= "INSERT INTO jogos_consoles (id_jogo, id_console)  ";
		$sql .= "SELECT $gameID, $consoleID ";
		$sql .= "WHERE NOT EXISTS (";
		$sql .= "SELECT 1 ";
		$sql .= "FROM jogos_consoles ";
		$sql .= "WHERE id_jogo = $gameID ";
		$sql .= "AND id_console = $consoleID";
		$sql .= ")";

		return $sql;
	}

	public static function insertGameGenero($gameID, $generoID)
	{
		$sql = "";
		$sql .= "INSERT INTO jogos_generos (id_jogo, id_genero)  ";
		$sql .= "SELECT $gameID, $generoID ";
		$sql .= "WHERE NOT EXISTS (";
		$sql .= "SELECT 1 ";
		$sql .= "FROM jogos_generos ";
		$sql .= "WHERE id_jogo = $gameID ";
		$sql .= "AND id_genero = $generoID";
		$sql .= ")";

		return $sql;
	}

	public static function insertAssessment($avaliacaoData)
	{
		$sql = "";
		$sql .= "INSERT INTO avaliacoes ";
		$sql .= "(id_usuario, id_jogo, id_console, avaliacao, comentario)  ";
		$sql .= "VALUES( ";
		$sql .= $avaliacaoData['id_usuario'] . ", ";
		$sql .= $avaliacaoData['id_jogo'] . ", ";
		$sql .= $avaliacaoData['id_console'] . ", ";
		$sql .= $avaliacaoData['nota'] . ", ";
		$sql .= "'" . $avaliacaoData['comentario'] . "') ";

		return $sql;
	}

	public static function getAllAssessment()
	{
		$sql = "";
		$sql .= "SELECT ";
		$sql .= "	a.id, ";
		$sql .= "	a.id_jogo, ";
		$sql .= "	j.titulo, ";
		$sql .= "	d.nome AS desenvolvedora, ";
		$sql .= "	a.id_console, ";
		$sql .= "	c.nome AS console_nome, ";
		$sql .= "	c.icone AS console_icone, ";
		$sql .= "	a.avaliacao, ";
		$sql .= "	a.comentario ";
		$sql .= "FROM avaliacoes a ";
		$sql .= "INNER JOIN jogos j ON a.id_jogo = j.id ";
		$sql .= "LEFT JOIN desenvolvedoras d ON j.id_desenvolvedora = d.id ";
		$sql .= "LEFT JOIN jogos_consoles jc ON j.id = jc.id_jogo ";
		$sql .= "LEFT JOIN consoles c ON jc.id_console = c.id ";
		$sql .= "GROUP BY j.id ";
		$sql .= "ORDER BY j.titulo; ";

		return $sql;
	}
}
