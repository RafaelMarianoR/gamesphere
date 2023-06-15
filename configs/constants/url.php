<?php

namespace Configs\Constants;

include_once $_SERVER['DOCUMENT_ROOT'] . '/gamesphere/autoload.php';

class URL
{
	public static function root()
	{
		return "http://" . $_SERVER['SERVER_ADDR'] . "/gamesphere";
	}

	public static function auth()
	{
		return URL::root() . "/auth";
	}
}

class Params
{
	public static function root()
	{
		return "http://" . $_SERVER['SERVER_ADDR'] . "/gamesphere";
	}
}
