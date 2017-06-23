<?
require_once("inc_qure.php");
$url = 'qure/responseexample.php';	// исправить на нужный url типа 'сайт/responseexample.php'





header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE HTML>
<head>
<meta charset='UTF-8'>
<title>QURE: Пример запроса</title>
<meta name=viewport content='width=device-width, initial-scale=1'>
</head>
<body>
<?





$queryArray=array(	'Сообщение'	=> "Привет, Камчатка!",
					'Вопрос'	=> "Какая погода?",
					'В городе'	=> "Мильково");
$answer = qure::sendArrayAndGetAnswerArray($queryArray, $url);
echo "<pre>";
echo "ЗАПРОС:\n";
print_r($queryArray);
echo "ОТВЕТ:\n";
print_r($answer);
echo "\n</pre>";

$queryArray=array(	'Сообщение'	=> "Привет, Камчатка!",
					'Вопрос'	=> "Какая погода?",
					'В городе'	=> "Токио");
$answer = qure::sendArrayAndGetAnswerArray($queryArray, $url);
echo "<pre>";
echo "ЗАПРОС:\n";
print_r($queryArray);
echo "ОТВЕТ:\n";
print_r($answer);
echo "\n</pre>";

$queryArray=array(	'Команда'	=> "Запиши себе",
					'В городе'	=> "Пермь",
					'Погода'	=> "Пасмурная");
$answer = qure::sendArrayAndGetAnswerArray($queryArray, $url);
echo "<pre>";
echo "ЗАПРОС:\n";
print_r($queryArray);
echo "ОТВЕТ:\n";
print_r($answer);
echo "\n</pre>";






?>
</body>
