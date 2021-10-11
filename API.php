<?php
namespace API;

use Vimeo\Vimeo;
use Vimeo\Exceptions\VimeoUploadException;

class API
{
	private $config;
	private $vimeo;

	function __construct($config) {
		$this->config = $config;
	}

	public function connect() {
		$this->vimeo = new Vimeo($this->config['client_id'], $this->config['client_secret']);
		$this->vimeo->setCURLOptions(array(CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false));
		$this->vimeo->setToken($this->config['token']);
		return ($this->vimeo ? true : false);
	}

	public function upload($file_name, $name, $description) {
		$result['status'] = false;
		$result['msg'] = '';
		$result['uri'] = '';
		try {
		    $result['uri'] = $this->vimeo->upload($file_name, array(
		        'name' => $name,
		        'description' => $description
		    ));
		    $result['msg'] = 'Фаил загружен';
		    $result['status'] = true;
		} catch (VimeoUploadException $e) {
			$result['msg'] = 'Ошибка загрузки файла: '.$e->getMessage();
		} catch (VimeoRequestException $e) {
			$result['msg'] = 'Ошибка отправки запроса: '.$e->getMessage();
		}
		return $result;
	}

	public function getStatus($uri) {
		$result['status'] = false;
		$result['msg'] = '';
		try {
		    $video_data = $this->vimeo->request($uri . '?fields=transcode.status');
		    $result['msg'] = 'Статус перекодирования видео: ' . $video_data['body']['transcode']['status'];
		    $result['status'] = true;
		} catch (VimeoRequestException $e) {
		    $result['msg'] = 'Ошибка отправки запроса: '.$e->getMessage();
		}
		return $result;
	}
}