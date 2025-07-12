// Загружаем данные при загрузке страницы
function initializeMasonry() {
	const grid = document.getElementById('reviews');
	
	// Проверяем, существует ли grid
	if (!grid) return;
	
	// Удаляем предыдущий экземпляр Masonry
	if (grid.masonry && typeof grid.masonry.destroy === 'function') {
		grid.masonry.destroy();
	}
	
	let gutter;
	if (window.innerWidth < 768) {
		gutter = 16; // для экранов менее 768px
	} else if (window.innerWidth <= 1280) {
		gutter = 30; // для экранов не более 1280px
	} else {
		gutter = 33; // для экранов более 1280px
	}
	
	// Подключаем Masonry
	grid.masonry = new Masonry(grid, {
		itemSelector: '.review',
		columnWidth: '.review',
		gutter: gutter,
	});
}

// Инициализируем Masonry
initializeMasonry();

// Слушаем изменения размера окна
window.addEventListener('resize', initializeMasonry);

document.addEventListener('DOMContentLoaded', () => {
	const modal = document.getElementById('modal');
	const openButton = document.getElementById('openModal');
	const closeButton = document.getElementById('closeModal');
	
	// Открытие модального окна
	openButton.addEventListener('click', () => {
		document.body.style.overflow = 'hidden';
		modal.style.opacity = '1';
		modal.style.pointerEvents = 'all';
		modal.classList.remove('hide');
		modal.classList.add('show');
	});
	
	// Закрытие модального окна
	closeButton.addEventListener('click', () => {
		modal.classList.remove('show');
		modal.classList.add('hide'); // добавляем класс "hide" для анимации закрытия
		
		// Скрываем модальное окно после завершения анимации
		setTimeout(() => {
			modal.style.opacity = '0'; // скрываем окно
			modal.style.pointerEvents = 'none'; // отключаем взаимодействие
			modal.classList.remove('hide'); // убираем "hide", чтобы сбросить состояние
			document.body.style.overflow = 'auto';
		}, 300);
	});
});

document.addEventListener('DOMContentLoaded', () => {
	function getReviewsData() {
		fetch('./../php/get-rating.php') // путь к PHP-скрипту
		.then(response => response.json())
		.then(data => {
			// Обновляем количество отзывов
			document.getElementById('reviewCountSpan').textContent = data.totalReviews;
			
			// Обновляем среднюю оценку
			const averageRating = data.averageRating;
			document.getElementById('averageRating').textContent = averageRating.toFixed(1).replace('.', ',');
			
			// Обновляем звёзды
			updateStars(Math.round(averageRating)); // обновляем звёзды в соответствии с округлённым рейтингом
		})
		.catch(error => console.error('Ошибка при загрузке данных: ', error));
	}
	
	// Функция для обновления звёзд
	function updateStars(rating) {
		const stars = document.querySelectorAll('#starsContainer svg');
		stars.forEach((star, index) => {
			if (index < rating) {
				star.style.fill = '#ffcc00'; // жёлтый для оценённых
			} else {
				star.style.fill = '#d3d3d3'; // серый для неоценённых
			}
		});
	}
	window.onload = getReviewsData;
});

document.addEventListener('DOMContentLoaded', () => {
	const deleteButtons = document.querySelectorAll('.delete-review');
	
	deleteButtons.forEach(button => {
		button.addEventListener('click', () => {
			const reviewId = button.getAttribute('data-review-id'); // получаем ID отзыва
			
			if (!reviewId) {
				alert('ID отзыва не найден!');
				return;
			}
			
			if (confirm('Вы уверены, что хотите удалить этот отзыв?')) {
				// Отправляем запрос на сервер для удаления
				fetch('./../php/delete-review.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify({ review_id: reviewId }), // отправляем ID отзыва
				})
				.then(response => response.json())
				.then(data => {
					console.log('Ответ от сервера: ', data); // логируем ответ для отладки
					if (data.status === 'success') {
						alert('Отзыв успешно удалён!');
						
						// Удаляем отзыв из DOM
						button.closest('.review').remove();
					} else {
						alert('Ошибка при удалении отзыва: ' + data.message);
					}
				})
				.catch(error => {
					console.error('Ошибка: ', error); // печатаем ошибку в консоль
					alert('Ошибка при отправке запроса на удаление!');
				});
			}
		});
	});
});

document.addEventListener('DOMContentLoaded', () => {
	const modal = document.getElementById('modal');
	const openModalButtons = document.querySelectorAll('.edit-review');
	const closeButton = document.getElementById('closeModal');
	const nameInput = document.getElementById('nameInput');
	const ratingInput = document.getElementById('ratingInput');
	const reviewInput = document.getElementById('review');
	const uploadedPhotosContainer = document.getElementById('uploaded-photos');
	const updateButton = document.getElementById('update'); // кнопка для обновления
	
	// Открытие модального окна
	openModalButtons.forEach(button => {
		button.addEventListener('click', () => {
			document.body.style.overflow = 'hidden';
			modal.style.opacity = '1'; // восстанавливаем видимость
			modal.style.pointerEvents = 'all'; // включаем взаимодействие
			modal.classList.remove('hide'); // убираем класс "hide", если есть
			modal.classList.add('show'); // добавляем класс "show" для появления
			
			// Извлекаем данные из кнопки редактирования
			const reviewId = button.getAttribute('data-id'); // ID отзыва
			const name = button.getAttribute('data-name');
			const rating = button.getAttribute('data-rating');
			const review = button.getAttribute('data-review');
			const files = button.getAttribute('data-files'); // получаем файлы
			
			// Заполняем поля в модальном окне
			nameInput.value = name;
			ratingInput.value = rating;
			reviewInput.value = review;
			
			// Запоминаем ID отзыва для дальнейшего обновления
			updateButton.setAttribute('data-id', reviewId);
			
			// Обновляем звёзды на основе рейтинга
			setStars(rating);
			
			// Обновляем фотографии
			updatePhotos(files);
		});
	});
	
	// Функция для установки звёздного рейтинга
	function setStars(rating) {
		const stars = document.querySelectorAll('#stars-grade svg');
		stars.forEach((star, index) => {
			if (index < rating) {
				star.classList.add('filled');
			} else {
				star.classList.remove('filled'); // убираем заполнение у остальных
			}
		});
	}
	
	// Функция для обновления фотографий
	function updatePhotos(files) {
		// Очищаем контейнер перед добавлением новых фотографий
		uploadedPhotosContainer.innerHTML = '';
		
		if (files) {
			// Преобразуем строку с файлами в массив
			const fileArray = files.split(',');
			
			i = 1;
			
			// Генерируем HTML для каждого файла
			fileArray.forEach(file => {
				const imagePath = `./../php/img/${file.trim()}`;
				const imgElement = document.createElement('div');
				imgElement.className = 'img-review';
				imgElement.innerHTML = `
					<img src="${imagePath}" alt="` + i + `-е фото с отзывом">
					<button class="delete-photo" id="deletePhoto` + i + `" title="Удалить файл" data-file="${file.trim()}">
						<svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="21" height="21">
							<path d="M23.707.293a1,1,0,0,0-1.414,0L12,10.586,1.707.293A1,1,0,0,0,.293,1.707L10.586,12,.293,22.293a1,1,0,1,0,1.414,1.414L12,13.414,22.293,23.707a1,1,0,0,0,1.414-1.414L13.414,12,23.707,1.707A1,1,0,0,0,23.707.293Z" />
						</svg>
					</button>
				`;
				uploadedPhotosContainer.appendChild(imgElement);
				
				const deletePhoto = document.getElementById('deletePhoto' + i);
				
				deletePhoto.addEventListener('click', () => {
					event.preventDefault(); // отменяем стандартное поведение кнопки
					
					imgElement.remove();
				});
				
				i++;
			});
		}
	}
	
	updateButton.addEventListener('click', (event) => {
		event.preventDefault(); // отменяем стандартное поведение кнопки
		
		// Собираем данные из формы
		const reviewId = updateButton.getAttribute('data-id');
		const name = nameInput.value.trim();
		const rating = ratingInput.value.trim();
		const review = reviewInput.value.trim();
		
		// Отправляем данные на сервер для обновления отзыва
		fetch('./../php/update-review.php', {
			method: 'POST',
			body: new URLSearchParams({
				'name': name,
				'rating': rating,
				'review': review,
				'review_id': reviewId // ID отзыва для обновления
			})
		})
		.then(response => response.json())
		.then(data => {
			if (data.status === 'success') {
				alert('Отзыв успешно обновлён');
				window.location.reload(); // перезагружаем страницу
			} else {
				alert('Ошибка: ' + data.message);
			}
		})
		.catch(error => {
			console.error('Ошибка: ', error);
			alert('Ошибка при обновлении отзыва');
		});
	});
	
	// Закрытие модального окна
	closeButton.addEventListener('click', () => {
		modal.classList.remove('show'); // убираем класс "show"
		modal.classList.add('hide'); // добавляем класс "hide" для анимации закрытия
		
		// Скрываем модальное окно после завершения анимации
		setTimeout(() => {
			modal.style.opacity = '0'; // скрываем окно
			modal.style.pointerEvents = 'none'; // отключаем взаимодействие
			modal.classList.remove('hide'); // убираем "hide", чтобы сбросить состояние
			document.body.style.overflow = 'auto';
		}, 300); // длительность совпадает с transition: 0.3s
	});
});

document.addEventListener('DOMContentLoaded', () => {
	const stars = document.querySelectorAll('#stars-grade svg');
	const ratingInput = document.getElementById('ratingInput'); // скрытое поле для рейтинга
	
	stars.forEach((star, index) => {
		star.addEventListener('click', () => {
			// Сбрасываем заполнение у всех звёзд
			stars.forEach((s, i) => {
				if (i <= index) {
					s.classList.add('filled'); // заполняем звёзды до текущей
				} else {
					s.classList.remove('filled'); // убираем заполнение у остальных
				}
			});
			
			// Устанавливаем значение рейтинга в скрытое поле
			ratingInput.value = index + 1;
		});
	});
})

document.addEventListener('DOMContentLoaded', () => {
	const stars = document.querySelectorAll('#stars-grade svg');
	const nameInput = document.getElementById('nameInput');
	const reviewInput = document.getElementById('review');
	const submitButton = document.getElementById('submit');
	const form = document.getElementById('reviewForm'); // предположим, что форма имеет id="reviewForm"
	const hintReviews = document.getElementById('hintReviews');
	const modal = document.getElementById('modal');
	
	const starsError = document.getElementById('starsError');
	const nameError = document.getElementById('nameError');
	const reviewError = document.getElementById('reviewError');
	
	let selectedStars = 0;
	
	// Обработка выбора звёзд
	stars.forEach((star, index) => {
		star.addEventListener('click', () => {
			selectedStars = index + 1; // сохраняем количество выбранных звёзд
			stars.forEach((s, i) => {
				if (i < selectedStars) {
					s.classList.add('filled');
				} else {
					s.classList.remove('filled');
				}
			});
			starsError.textContent = ''; // убираем текст ошибки, если звёзды выбраны
		});
	});
	
	// Убираем текст ошибки при вводе текста
	function handleInputValidation(input, errorElement) {
		input.addEventListener('input', () => {
			if (input.value.trim() !== '') {
				errorElement.textContent = ''; // сбрасываем текст ошибки
				input.classList.remove('error');
			}
		});
	}
	
	// Добавляем обработчики ввода для полей
	handleInputValidation(nameInput, nameError);
	handleInputValidation(reviewInput, reviewError);
	
	// Валидация при отправке формы
	submitButton.addEventListener('click', (event) => {
		event.preventDefault(); // предотвращаем отправку формы
		
		let isValid = true;
		
		// Проверка звёзд
		if (selectedStars === 0) {
			starsError.textContent = 'Проставьте оценку';
			isValid = false;
		} else {
			starsError.textContent = '';
		}
		
		// Проверка имени
		if (nameInput.value.trim() === '') {
			nameError.textContent = 'Заполните имя';
			nameInput.classList.add('error');
			isValid = false;
		} else {
			nameError.textContent = '';
			nameInput.classList.remove('error');
		}
		
		// Проверка отзыва
		if (reviewInput.value.trim() === '') {
			reviewError.textContent = 'Заполните отзыв';
			reviewInput.classList.add('error');
			isValid = false;
		} else {
			reviewError.textContent = '';
			reviewInput.classList.remove('error');
		}
		
		if (isValid) {
			const formData = new FormData(form); // собираем данные формы
			
			fetch('./../php/submit-review.php', { // путь к PHP-скрипту
				method: 'POST',
				body: formData
			})
			.then(response => {
				if (response.ok) { // если статус HTTP-ответа успешный
					return response.json(); // парсим JSON-ответ
				} else {
					throw new Error(`Ошибка при отправке данных: ${response.status}`); // генерируем ошибку с кодом
				}
			})
			.then(data => {
				// Проверяем успешность ответа
				console.log('Данные успешно отправлены: ', data);
				
				// Очистка формы
				form.reset();
				selectedStars = 0;
				stars.forEach((s) => s.classList.remove('filled'));
				
				// Закрытие модального окна
				modal.classList.remove('show');
				modal.classList.add('hide');
				setTimeout(() => {
					modal.style.opacity = '0';
					modal.style.pointerEvents = 'none';
					modal.classList.remove('hide');
					document.body.style.overflow = 'auto';
				}, 300);
				
				// Показ уведомления
				hintReviews.style.display = 'flex';
				setTimeout(() => {
					hintReviews.style.display = 'none';
					form.style.display = 'block';
				}, 4000);
			})
			.catch(error => {
				console.error('Ошибка: ', error); // логируем ошибку
				alert('Не удалось отправить данные. Попробуйте позже!');
			});
		}
	});
});

document.getElementById('file').addEventListener('change', function(event) {
	const files = event.target.files; // получаем список файлов
	const maxFiles = 2; // максимальное количество файлов
	const errorElement = document.getElementById('fileError');
	
	// Если количество файлов больше максимума, показываем ошибку
	if (files.length > maxFiles) {
		errorElement.textContent = `Вы можете выбрать не более ${maxFiles} файлов.`;
		event.target.value = ''; // очистить выбранные файлы
	} else {
		errorElement.textContent = ''; // убираем сообщение об ошибке
	}
});