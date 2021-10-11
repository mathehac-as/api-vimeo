<?php
require_once('api-vimeo.php');

$file_name = '/home/ment648401/mentoryclub.com/docs/test/media/111.mp4';
$name = 'Новое видео';
$description = 'Описание';

$api = new \API\API($config);
if($api->connect())
{
    $result = $api->getStatus('/videos/591568193');
    var_dump($result);
	$result = $api->getStatus('/videos/591702834');
    var_dump($result);
    //$result = $api->upload($file_name, $name, $description);
    //var_dump($result);
}