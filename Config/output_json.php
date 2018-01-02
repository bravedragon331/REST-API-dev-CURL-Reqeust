<?php

// This is the output class

class Output {
	
	public function Success($data){
		$response = array();
		
		$response['success'] = true;
		$response['token'] = Accesscontrol::getToken();
		$response['tokenexpiry'] = date("Y-m-d H:i:s",Accesscontrol::getTokenExpiry());
		$response['userid'] = Accesscontrol::getUserID();
		
		if ($data == "none") {
			Output::NoResults();
		}
		$response['data'] = $data;
		echo json_encode($response);
		die;
	}

	public function NotFound($message){
		$response = array();
		$response['success'] = false;
		$response['data']['message'] = $message;
		$response['data']['code'] = 404;
		echo json_encode($response);
		die;
	}

	public function Error($message){
		$response = array();
		$response['success'] = false;
		$response['data']['message'] = $message;
		$response['data']['code'] = 500;
		echo json_encode($response);
		die;
	}

	public function Forbidden($message){
		$response = array();
		$response['success'] = false;
		$response['data']['message'] = $message;
		$response['data']['code'] = 403;
		echo json_encode($response);
		die;
	}

	public function NoResults(){
		$response = array();
		$response['success'] = true;
		$response['data']['message'] = "No results matched your query";
		echo json_encode($response);
		die;
	}

}