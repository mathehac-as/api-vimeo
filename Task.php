<?php
namespace Task;

class Task
{
	private $tasks = [];
	private $db;
	private $config;

	function __construct($db) {
		$this->db = $db;
	}

	public function connect($config) {
		$this->config = $config;
		return $this->db->connect('mysql:host='.$config['host'].';dbname='.$config['dbname'].';charset='.$config['charset'], $config['username'], $config['password']);
	}

	public function getTasks() {
		return $this->db->table('vimeo_tasks')->select('*', ['status' => 1])->retrieve();
	}

	public function setTaskStatus($id, $status) {
		$date = new \DateTime();
		$this->db->table('vimeo_tasks')->update([ 'status' => $status, 'processed_time' => $date->format('Y-m-d H:i:s') ], [ 'task_id' => $id ]);
	}

	public function setVideos($data) {
		return $this->db->table('vimeo_videos')->multi_insert( 'name', 'description', 'uri', 'status' , $data);
	}

	public function getError() {
		return $this->db->getPDOException();
	}
}