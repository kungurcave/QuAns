<?php
require_once("inc_qure.php");

// получение сообщения
$queryArray = qure::getPostArray();
// тут можно что-то делать, но не надо ничего выводить


// пример имеющейся информации
$weather = array();
$weather['Мильково'] = 'отличная, солнечая';
$weather['Вилючинск'] = 'не очень, сильный ветер';


// подготовка и отправка ответа
$response=array();
if( !is_array($queryArray)) {
	$response['error'] = "Не удалось получить массив с запросом";
	qure::sendAnswerArray($response);
}

// 'Вопрос'=>"Какая погода?"
// 'В городе'
if( array_key_exists('Вопрос',$queryArray)) {
	if( $queryArray['Вопрос'] == 'Какая погода?' ) {
		
		// нет нужных ключей
		if( !array_key_exists('В городе',$queryArray)) {
			$response['error'] = "Не указан город";
			qure::sendAnswerArray($response);
		}
		
		$city = $queryArray['В городе'];
		if( !array_key_exists($city, $weather)) {
			$response['error'] = "Нет информации о погоде в городе ".$city;
			qure::sendAnswerArray($response);
		}
		
		// отвечаем
		$response['Погода в городе '.$city] = $weather[$city];
		qure::sendAnswerArray($response);
		
	}
	else {
		$response['error'] = "Непонятный вопрос '".$queryArray['Вопрос']."'";
		qure::sendAnswerArray($response);
	}
}

// 'Команда'=>"Запиши себе"
// 'В городе'
// 'Погода'
elseif (array_key_exists('Команда',$queryArray)) {
	if( $queryArray['Команда'] == 'Запиши себе' ) {
	
		// нет нужных ключей
		if( !array_key_exists('В городе',$queryArray)) {
			$response['error'] = "Не указан город";
			qure::sendAnswerArray($response);
		}
		
		if( !array_key_exists('Погода',$queryArray)) {
			$response['error'] = "Не указана погода";
			qure::sendAnswerArray($response);
		}
		
		// выполняем и отвечаем
		SaveToBase($queryArray['В городе'],$queryArray['Погода']);
		$response['Спасибо'] = "Записал";
		qure::sendAnswerArray($response);
		
	}
	else {
		$response['error'] = "Непонятная команда '".$queryArray['Команда']."'";
		qure::sendAnswerArray($response);
	}
}

// ОШИБКА
else {
	$response['error'] = "Не задан вопрос и не дана команда";
	qure::sendAnswerArray($response);
}


function SaveToBase($city,$weather) {
	// Делаем вид, что что-то делаем
}
