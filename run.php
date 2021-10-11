<?php
require_once('api-vimeo.php');

use JRCologne\Utils\Database\DB;
use JRCologne\Utils\Database\QueryBuilder;

try {
	$db = new DB(new QueryBuilder);
	$tasks = new \Task\Task($db);
	if($tasks->connect($config['db']))
	{
		$task_list = $tasks->getTasks();
		foreach ($task_list as $value) 
		{
			try {
				$id = $value['task_id'];
				$file_name = $value['file_video'];
				$name = $value['name_video'];
				$description = $value['description_video'];
				$api = new \API\API($config['vimeo']);
				if($api->connect())
				{
				    //$result = $api->getStatus('/videos/591568193');
				    //var_dump($result);
				    $file = $config['path_file'].$file_name;
				    if(file_exists($file))
				    {
					    $result = $api->upload($file, $name, $description);
					    if(isset($result['status']) && $result['status'])
					    {
						    $data = [ [ 'name' => $name, 'description' => $description, 'uri' => $result['uri'], 'status' => 1, ] ];
						    $result = $tasks->setVideos($data);
						    $tasks->setTaskStatus($id, 2);
						}
						else
						{
							throw new Exception('Api no upload file - '.$file.'; '.print_r($result, true), 4);
							$tasks->setTaskStatus($id, 3);
						}
					}
					else
					{
						throw new Exception('File not exists - '.$file, 3);
						$tasks->setTaskStatus($id, 3);
					}
				}
				else
				{
					throw new Exception('Api no connected', 2);
				}
			} catch(Exception $e){
				echo $e->getMessage()."\n";
			}
		}	
	}
	else
	{
		throw new Exception('Tasks no connected', 1);
	}
} catch(Exception $e){
	echo $e->getMessage()."\n";
}