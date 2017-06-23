<?php


class qure {

	// ЗДЕСЬ НУЖНО ВВЕСТИ СВОЙ СЕКРКРЕТНЫЙ КЛЮЧ, ОДИНАКОВЫЙ ДЛЯ ВСЕХ САЙТОВ
	const SALT="secretkey";
	
	
	// ВЫЗЫВАЕТСЯ НА САЙТЕ, ООТПРАВЛЯЮЩЕМ ЗАПРОС
	
	// отправка массива-запроса и получение массива-ответа
	// $arr - массив запроса
	// $url - адрес, куда отправляется запрос
	// возвращает функция массив-ответ
	public static
	function sendArrayAndGetAnswerArray($arr, $url) {
		
		if (self::SALT=="secretkey") {
			return array('error' => "Не настроен секретный ключ!");
		}
		
		// данные для запроса
		$postData = self::prepareArrayMd5($arr);
		
		if( $curl = curl_init() ) {			
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($curl, CURLOPT_REFERER, "http://ya.ru");
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0");
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

			$responce = curl_exec($curl);
			if ($responce===FALSE) {
				$responce=array('Curl error' => curl_error($curl));
				curl_close($curl);
				return $responce;
			}
			curl_close($curl);
		}
		
		$answerArr = json_decode($responce,TRUE);
		
		if(self::testJsonMd5($answerArr)) {
			$result = json_decode($answerArr['message'],TRUE);
		}
		else {
			$result = array('error' => "Неверный хеш в ответе");
			var_dump($responce);
		}

		return $result;

	}

		
	
	// ВЫЗЫВАЕТСЯ НА ОТВЕЧАЮЩЕМ САЙТЕ
	
	// проверка полученного методом POST массива
	public static
	function getPostArray() {
		
		$post=json_decode(file_get_contents("php://input"),TRUE);
		
		if(self::testJsonMd5($post)) {
			$result = json_decode($post['message'],TRUE);
		}
		else {
			$result = FALSE;
			//error_log (__FILE__."\nПолученный POST не прошел проверку:\n".print_r($post,TRUE));
			echo __FILE__."\nПолученный POST не прошел проверку:\n".print_r($post,TRUE);	// TODO
		}

		return $result;

	}
	
	// печать закодированного ответа
	public static
	function sendAnswerArray($arr) {
		header('Content-type: application/json; charset=utf-8');
		echo json_encode(self::prepareArrayMd5($arr));
		exit;
	}


	
	// ОБЩИЕ ВНУТРЕННИЕ ФУНКЦИИ
	
	// проверка хеша
	private static
	function testJsonMd5($arr) {
		
		if( !is_array($arr) OR !array_key_exists('message',$arr) OR !array_key_exists('hash',$arr)) {
			return FALSE;
		}
		
		//return ( md5($arr['message'].self::SALT) == $arr['hash'] );
		return ( hash_hmac('ripemd160', $arr['message'], self::SALT) == $arr['hash'] );
		
	}

	// прицепка хеша к сообщению
	private static
	function prepareArrayMd5($arr) {

		$message=json_encode($arr);
		//$hash = md5($message.self::SALT);
		$hash =  hash_hmac('ripemd160', $message, self::SALT);
		
		return array('message'=>$message,'hash'=>$hash);

	}

}

