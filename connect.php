<?php
	// Подключение к базе данных
	$servername = "MySQL-8.2"; // сервер базы данных
	$username = "admin"; // имя пользователя базы данных
	$password = "it211_balym!"; // пароль пользователя к базе данных
	$dbname = "reviews"; // имя базы данных
	
	// Создание подключения
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	// Проверка подключения
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
?>