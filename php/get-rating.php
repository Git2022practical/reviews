<?php
	require '../connect.php'; // подключаем файл с базой данных
	
	// Запрос для получения всех отзывов
	$sql = "SELECT rating FROM users_reviews";
	$result = $conn->query($sql);
	
	// Переменные для подсчёта
	$totalReviews = 0;
	$totalRating = 0;
	
	// Подсчитываем количество отзывов и суммарную оценку
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$totalReviews++;
			$totalRating += $row['rating'];
		}
	} else {
		$totalReviews = 0;
		$totalRating = 0;
	}
	
	// Вычисляем среднюю оценку
	$averageRating = $totalReviews > 0 ? round($totalRating / $totalReviews, 1) : 0;
	
	// Закрытие соединения
	$conn->close();
	
	// Вывод данных
	echo json_encode([
		'totalReviews' => $totalReviews,
		'averageRating' => $averageRating
	]);
?>