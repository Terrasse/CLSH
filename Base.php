<?php
class Base {
	static private $pdo;
	static private function connect() {
		try {
			include_once 'BDInformation.ini';
			$dsn = "mysql:host=$host;dbname=$dbname";
			self::$pdo = new PDO($dsn, $login, $pwd, $param);
			self::$pdo -> exec("SET CHARACTER SET utf8");
		} catch(PDOException $e) {
			throw new BaseException("connexion :$dsn " . $e -> getMessage() . '<br>');
		}
	}

	static function getConnexion() {
		if (!isset(self::$pdo)) {
			self::connect();
		}
		return self::$pdo;
	}

}
?>
