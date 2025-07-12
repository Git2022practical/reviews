<?php
	session_start();
	
	require 'connect.php'; // подключаем файл с базой данных
	
	// Проверяем, вошёл ли пользователь
	if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
		header('Location: admin.php'); // перенаправляем на страницу admin.php
	}
	
	// Обработка формы
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$login = $_POST['username'] ?? '';
		$pwd = $_POST['password'] ?? '';
		
		// Правильные логин и пароль
		$validUsername = $username;
		$validPassword = $password;
		
		if ($login === $username && $pwd === $password) {
			$_SESSION['logged_in'] = true;
			header('Location: admin.php'); // перенаправляем на admin.php
		} else {
			$error = 'Неверный логин или пароль!';
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Вход в систему</title>
		<link href="./css/styles.css" rel="stylesheet">
	</head>
	<body id="loginBody">
		<h1>Вход в систему</h1>
		<?php
			if (isset($error)) {
				echo '<p>'.htmlspecialchars($error).'</p>';
			}
		?>
		<form method="post" action="./login.php" id="loginForm">
			<label for="username">Логин:</label>
			<input type="text" name="username" id="username" required>
			<label for="password">Пароль:</label>
			<input type="password" name="password" id="password" required>
			<button id="loginButton">Войти</button>
		</form>
	</body>
</html>