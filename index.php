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
				<div id="titleBlock">
					<h2>Отзывы наших покупателей</h2>
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
					<button id="openModal">Написать отзыв</button>
				</div>
				<div id="reviews">
					<?php
						require 'connect.php'; // подключаем файл с базой данных
						
						// Запрос для получения отзывов с изображениями
						$sql = "SELECT name, review, rating, files FROM users_reviews"; // в данном случае предполагается, что files хранит строки путей, разделённые запятой
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
					<h2>Написать отзыв</h2>
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
							<label>Добавьте фотографии или видео</label>
							<div id="button-upload">
								<label id="custum-file-upload" for="file">
									<div id="icon">
										<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" height="35" viewBox="0 0 24 24" width="35" data-name="Layer 1">
											<path d="m16 16a1 1 0 0 1 -1 1h-2v2a1 1 0 0 1 -2 0v-2h-2a1 1 0 0 1 0-2h2v-2a1 1 0 0 1 2 0v2h2a1 1 0 0 1 1 1zm6-5.515v8.515a5.006 5.006 0 0 1 -5 5h-10a5.006 5.006 0 0 1 -5-5v-14a5.006 5.006 0 0 1 5-5h4.515a6.958 6.958 0 0 1 4.95 2.05l3.484 3.486a6.951 6.951 0 0 1 2.051 4.949zm-6.949-7.021a5.01 5.01 0 0 0 -1.051-.78v4.316a1 1 0 0 0 1 1h4.316a4.983 4.983 0 0 0 -.781-1.05zm4.949 7.021c0-.165-.032-.323-.047-.485h-4.953a3 3 0 0 1 -3-3v-4.953c-.162-.015-.321-.047-.485-.047h-4.515a3 3 0 0 0 -3 3v14a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3z" />
										</svg>
									</div>
									<input type="file" id="file" name="files[]" multiple>
								</label>
								<span>Нажмите на кнопку или перетащите файл в эту область</span>
							</div>
							<div id="fileError" class="error"></div>
						</div>
						
						<!-- Кнопка отправки -->
						<div id="submitContainer">
							<button id="submit">Отправить отзыв</button>
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