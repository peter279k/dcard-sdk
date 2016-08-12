<?php
	namespace Dcard\sdk;
	
	use GuzzleHttp\Client;
	use GuzzleHttp\Exception\RequestException;
	use GuzzleHttp\Cookie\CookieJar;
	
	use Symfony\Component\DomCrawler\Crawler;
	
	class HttpRequest {
		
		public function __construct() {
			
		}
		
		public function send($data) {
			$response = array();

			$client = new Client();
			$jar = new CookieJar();
			
			if($data["http_method"] === "POST") {
				$response = $this -> handlePost($data, $client, $jar);
			}

			else {
				$response = $this -> handleCond($data, $client, $jar);
			}
			
			$response = $this -> handleRes($response);
			
			return $response;
		}
		
		private function handleRes($response) {
			if(is_object($response))
				$response = $response -> getBody();
			
			if(is_array($response))
				$response = json_encode($response);
			
			return $response;
		}
		
		private function handlePost($data, Client $client, CookieJar $jar) {
			$response = $this -> loginAuth($data, $client, "", $jar);
				
			if($data["request_url"] === "https://www.dcard.tw/_api/dcard/accept") {
				$response = $this -> sendAccept($data, $client, $jar);
			}
			
			return $response;
		}
		
		private function handleGet($data, Client $client, CookieJar $jar) {
			$objArr = $this -> preLogin($data, $client, $jar);
			
			$data = $objArr[0];
			$client = $objArr[1];
			$jar = $objArr[2];
			
			$response = $client -> request($data["http_method"], $data["request_url"], ["verify" => false, 'cookies' => $jar]);
			return $response;
		}
		
		private function handleDefault($data, Client $client, CookieJar $jar) {
			try {
				$response = $client -> request($data["http_method"], $data["request_url"], ["verify" => false, 'cookies' => $jar]);
			}
			catch(RequestException $e) {
				$response = $e -> getResponse();
			}
			
			return $response;
		}
		
		private function handleCond($data, Client $client, CookieJar $jar) {
			switch($data["request_url"]) {
				//need to login
				
				case "https://www.dcard.tw/_api/notifications":
				case "https://www.dcard.tw/_api/dcard":
				case "https://www.dcard.tw/_api/me":
					$response = $this -> handleGet($data, $client, $jar);
					break;
				case "https://www.dcard.tw/logout":
					$response = $this -> logout($data, $client, $jar);
					break;
				default:
					$response = $this -> handleDefault($data, $client, $jar);
			}
			
			return $response;
		}
		
		private function logout($data, Client $client, CookieJar $jar) {
			$response = $client -> request($data["http_method"], $data["request_url"], ["verify" => false, 'cookies' => $jar]);
			$response = array();
			$response["success"] = "success";
			$response["success_description"] = "logout success";
			$response["scope"] = "logout";
			return $response;
		}
		
		private function getToken($ReqUrl, Client $client, CookieJar $jar) {
			$response = $client -> request('GET', $ReqUrl, ["verify" => false, 'cookies' => $jar]);
			
			$crawler = new Crawler($response -> getBody() -> getContents());
			
			$ScriptTag = $crawler -> filter('script');
	
			foreach($ScriptTag as $key => $value) {
				$crawler = new Crawler($value);
				$ScriptJson = $crawler -> filter('script') -> text();
		
				$JsonArr = array();
				
				if(stristr($ScriptJson, "window." . '$'. "STATE=") !== false) {
					$ScriptJson = str_replace("window." . '$'. "STATE=", "", $ScriptJson);
					$JsonArr = json_decode($ScriptJson, true);
					break;
				}
			
			}
	
			return $JsonArr['app']['csrfToken'];
		}
		
		private function loginAuth($data, Client $client, $ReqClient, CookieJar $jar) {
			$token = $this -> getToken('https://www.dcard.tw/login', $client, $jar);
			
			try {
				$response = $client -> request("POST", $data["request_url"], [
					"verify" => false,
					'json' => ['email' => $data["account"], 'password' => $data["password"]],
					'cookies' => $jar,
					'headers' => ['x-csrf-token' => $token]
				]);
					
				$ResStr = $response -> getBody() -> getContents();
				$response = array();
			
				if(strlen($ResStr) === 0) {
					$response["success"] = "success";
					$response["success_description"] = "login success";
					$response["scope"] = "login";
				}
			}
			catch(RequestException $e) {
				$response["error"] = "failed";
				$response["error_description"] = "login failed";
				$response["scope"] = "login";
			}
			
			if($ReqClient === "return-client")
				return $client;
			else
				return $response;
		}
		
		private function sendAccept($data, Client $client, CookieJar $jar) {
			$objArr = $this -> preLogin($data, $client, $jar);
			
			$data = $objArr[0];
			$client = $objArr[1];
			$jar = $objArr[2];
			
			$token = $this -> getToken('https://www.dcard.tw/dcard', $client, $jar);
			
			$response = $client -> request("POST", $data["request_url"], [
				"verify" => false,
				'json' => ['firstMessage' => $data['message']],
				'cookies' => $jar,
				'headers' => ['x-csrf-token' => $token]
			]);
			
			return $response;
		}
		
		private function preLogin($data, Client $client, CookieJar $jar) {
			$url = $data["request_url"];
			$data["request_url"] = "https://www.dcard.tw/_api/sessions";
			$client = $this -> loginAuth($data, $client, "return-client", $jar);
			$data["request_url"] = $url;
			
			return array($data, $client, $jar);
		}
		
	}
?>
