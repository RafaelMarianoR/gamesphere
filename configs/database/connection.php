<?php

namespace Configs\Database;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

class Connection
{
	private $dbConn;
	public function connect()
	{
		$host = "localhost";
		$username = "root";
		$password = "";
		$dbname = "gamesphere";

		$this->dbConn = mysqli_connect($host, $username, $password, $dbname);

		if ($this->dbConn->connect_error)
			die('Erro na conexão com o banco de dados: ' . $this->dbConn->connect_error);

		return $this->dbConn;
	}

	public function close()
	{
		$this->dbConn->close();
	}

	public static function openConn()
	{
		return new Connection();
	}

	public static function conn()
	{
		$host = "localhost";
		$username = "root";
		$password = "";
		$dbname = "gamesphere";

		return mysqli_connect($host, $username, $password, $dbname);
	}
}
