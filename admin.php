<?php
	session_start();
	
	// Проверяем, авторизован ли пользователь от имени администратора
	if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
		header('Location: login.php'); // перенаправляем на страницу входа
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Отзывы наших покупателей</title>
		<link href="./css/styles.css" rel="stylesheet">
	</head>
	<body>
		<content>
			<main>
				<a href="./../php/logout.php" id="logout-link">Выход</a>
				<div id="titleBlock">
					<h2>Страница администратора</h2>
					<h3>Отзывы наших покупателей</h3>
				</div>
				<div id="reviewsContainer">
					<div id="reviewsInfo">
						<div id="ratingBlock">
							<span>Средняя оценка:</span>
							<div id="ratingContent">
								<div id="starsContainer">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ffcc00">
										<path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z" />
									</svg>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ffcc00">
										<path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z" />
									</svg>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ffcc00">
										<path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z" />
									</svg>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ffcc00">
										<path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z" />
									</svg>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ffcc00">
										<path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z" />
									</svg>
								</div>
								<h3 id="averageRating">0</h3>
							</div>
						</div>
						<div id="quantity">
							<span id="reviewsCount">Количество отзывов:</span>
							<span id="reviewCountSpan">0</span>
						</div>
					</div>
				</div>
				<div id="reviews">
					<?php
						require 'connect.php'; // подключаем файл с базой данных
						
						// Запрос для получения отзывов с изображениями
						$sql = "SELECT id, name, review, rating, files FROM users_reviews"; // в данном случае предполагается, что files хранит строки путей, разделённые запятой
						$result = $conn->query($sql);
						
						// Массив для хранения HTML-кода отзывов
						$reviews_html = '';
						
						// Проверка наличия отзывов
						if ($result->num_rows > 0) {
							// Проходим по всем отзывам и генерируем HTML для каждого отзыва
							while ($row = $result->fetch_assoc()) {
								// Декодируем строку с изображениями, разделяя её по запятой
								$image_paths = explode(',', $row['files']); // разделяем строку на массив путей
								
								// Формирование звёздного рейтинга
								$stars_html = '';
								for ($i = 0; $i < 5; $i++) {
									if ($i < $row['rating']) {
										$stars_html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ffcc00">
											<path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z" />
										</svg>';
									} else {
										$stars_html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#d3d3d3">
											<path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z" />
										</svg>';
									}
								}
								
								// Генерация HTML для отзыва
								$reviews_html .= '
									<div class="review">
										<div class="name-review">
											<h4>' . htmlspecialchars($row['name']) . '</h4>
											<div class="stars-review">' . $stars_html . '</div>
											<div class="admin-buttons">
												<button class="edit-review" title="Редактировать отзыв" data-id="'.htmlspecialchars($row['id']). '" data-name="'.htmlspecialchars($row['name']) .'" data-rating="'.htmlspecialchars($row['rating']) .'" data-review="'.$row['review'].'" data-files="'.htmlspecialchars($row['files']) .'">
													<svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="512" height="512">
														<path d="M22.853,1.148a3.626,3.626,0,0,0-5.124,0L1.465,17.412A4.968,4.968,0,0,0,0,20.947V23a1,1,0,0,0,1,1H3.053a4.966,4.966,0,0,0,3.535-1.464L22.853,6.271A3.626,3.626,0,0,0,22.853,1.148ZM5.174,21.122A3.022,3.022,0,0,1,3.053,22H2V20.947a2.98,2.98,0,0,1,.879-2.121L15.222,6.483l2.3,2.3ZM21.438,4.857,18.932,7.364l-2.3-2.295,2.507-2.507a1.623,1.623,0,1,1,2.295,2.3Z" />
													</svg>
												</button>
												<button class="delete-review" title="Удалить отзыв" data-review-id="'.htmlspecialchars($row['id']). '">
													<svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="512" height="512">
														<path d="M21,4H17.9A5.009,5.009,0,0,0,13,0H11A5.009,5.009,0,0,0,6.1,4H3A1,1,0,0,0,3,6H4V19a5.006,5.006,0,0,0,5,5h6a5.006,5.006,0,0,0,5-5V6h1a1,1,0,0,0,0-2ZM11,2h2a3.006,3.006,0,0,1,2.829,2H8.171A3.006,3.006,0,0,1,11,2Zm7,17a3,3,0,0,1-3,3H9a3,3,0,0,1-3-3V6H18Z" />
														<path d="M10,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,10,18Z" />
														<path d="M14,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,14,18Z" />
													</svg>
												</button>
											</div>
										</div>
										<div class="text-review">
											<p>' . htmlspecialchars($row['review']) . '</p>
										</div>';
								
								// Проверка на наличие изображений
								if (!empty($image_paths) && count($image_paths) > 0 && !empty($image_paths[0])) {
									$i = 1;
									
									// Если изображения есть, создаём блок для них
									$reviews_html .= '<div class="imgs">';
									foreach ($image_paths as $image) {
										$image_path = "./../php/img/" . htmlspecialchars($image);
										$reviews_html .= '<div class="img-review"><img src="' . $image_path . '" alt="'.$i.'-е фото с отзывом"></div>';
										
										$i++;
									}
									$reviews_html .= '</div>';
								}
								
								// Закрываем блок отзыва
								$reviews_html .= '</div>';
							}
						} else {
							$reviews_html = '<p>Нет отзывов</p>';
						}
						
						// Закрытие соединения
						$conn->close();
						
						// Выводим все отзывы
						echo $reviews_html;
					?>
				</div>
			</main>
			<div id="modal">
				<div id="modalContent">
					<button id="closeModal">
						<svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="512" height="512">
							<path d="M23.707.293h0a1,1,0,0,0-1.414,0L12,10.586,1.707.293a1,1,0,0,0-1.414,0h0a1,1,0,0,0,0,1.414L10.586,12,.293,22.293a1,1,0,0,0,0,1.414h0a1,1,0,0,0,1.414,0L12,13.414,22.293,23.707a1,1,0,0,0,1.414,0h0a1,1,0,0,0,0-1.414L13.414,12,23.707,1.707A1,1,0,0,0,23.707.293Z" />
						</svg>
					</button>
					<h2>Изменить отзыв</h2>
					<form method="post" enctype="multipart/form-data" id="reviewForm">
						<!-- Оценка -->
						<div id="grade">
							<label><span>*</span> Оцените товар</label>
							<div id="stars-grade">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ccc">
									<path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z" />
								</svg>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ccc">
									<path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z" />
								</svg>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ccc">
									<path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z" />
								</svg>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ccc">
									<path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z" />
								</svg>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ccc">
									<path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z" />
								</svg>
							</div>
							<input type="hidden" name="rating" id="ratingInput" value="0"> <!-- Поле для рейтинга -->
							<div id="starsError" class="error"></div>
						</div>
						
						<!-- Имя -->
						<div class="inputGroup">
							<input type="text" id="nameInput" name="name" autocomplete="off" required>
							<label for="name"><span>*</span> Имя</label>
							<div id="nameError" class="error"></div>
						</div>
						
						<!-- Отзыв -->
						<div class="textareaGroup">
							<textarea id="review" name="review" rows="9" required></textarea>
							<label for="review"><span>*</span> Ваш отзыв</label>
							<div id="reviewError" class="error"></div>
						</div>
						
						<!-- Загрузка файлов -->
						<div id="file-upload">
							<div id="uploaded-photos">
								<?php
									// Проверяем наличие изображений
									if (!empty($image_paths) && count($image_paths) > 0 && !empty($image_paths[0])) {
										$i = 1;
										
										foreach ($image_paths as $image) {
											// Генерируем пути к изображениям
											$image_path = "./../php/img/" . htmlspecialchars($image);
											echo '<div class="img-review">
												<img src="' . $image_path . '" alt="'.$i.'-е фото с отзывом">
												<button data-file="' . htmlspecialchars($image) . '">
													<svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="21" height="21">
														<path d="M23.707.293a1,1,0,0,0-1.414,0L12,10.586,1.707.293A1,1,0,0,0,.293,1.707L10.586,12,.293,22.293a1,1,0,1,0,1.414,1.414L12,13.414,22.293,23.707a1,1,0,0,0,1.414-1.414L13.414,12,23.707,1.707A1,1,0,0,0,23.707.293Z" />
													</svg>
												</button>
											</div>';
											
											$i++;
										}
									}
								?>
							</div>
						</div>
						
						<!-- Кнопка отправки -->
						<div id="submitContainer">
							<button id="update">Изменить отзыв</button>
						</div>
					</form>
				</div>
			</div>
			<div id="hintReviews" style="display: none;">
				<p>Ваш отзыв успешно отправлен!</p>
			</div>
		</content>
	</body>
	<script src="./scripts/masonry.pkgd.min.js"></script>
	<script src="./scripts/scripts.js"></script>
</html>