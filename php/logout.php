<?php
	session_start();
	session_destroy(); // уничтожаем сессию
	header('Location: ../login.php'); // перенаправляем на страницу входа
?>