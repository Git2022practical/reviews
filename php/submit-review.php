<?php
	require '../connect.php'; // подключаем файл с базой данных
	
	header('Content-Type: application/json');
	
	try {
		$pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		// Получаем данные
		$name = $_POST['name'] ?? '';
		$review = $_POST['review'] ?? '';
		$rating = $_POST['rating'] ?? 0;
		
		// Проверяем загруженные файлы
		$uploadedFiles = [];
		if (isset($_FILES['files'])) {
			foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
				$fileName = basename($_FILES['files']['name'][$key]);
				$uploadDir = __DIR__ . '/img/';
				$uploadPath = $uploadDir . $fileName;
				
				if (move_uploaded_file($tmpName, $uploadPath)) {
					$uploadedFiles[] = $fileName;
				}
			}
		}
		
		// Сохраняем данные в базу
		$stmt = $pdo->prepare("INSERT INTO users_reviews (name, review, rating, files) VALUES (:name, :review, :rating, :files)");
		$stmt->execute([
			':name' => $name,
			':review' => $review,
			':rating' => $rating,
			':files' => implode(',', $uploadedFiles) // сохраняем список файлов
		]);
		
		echo json_encode(['status' => 'success', 'message' => 'Отзыв успешно сохранён']);
	} catch (Exception $e) {
		echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
	}
?>