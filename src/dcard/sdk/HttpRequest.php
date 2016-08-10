<?php
	namespace Dcard\sdk;
	
	use GuzzleHttp\Client;
	use GuzzleHttp\Exception\RequestException;
	
	use Symfony\Component\DomCrawler\Crawler;
	
	class HttpRequest {
		public function __construct() {}
		
		public static function send($data) {
			$response = array();

			$client = $this -> InitialClient();
					
			if($data["http_method"] == "GET") {
				$response = $client -> request($data["http_method"], $data["request_url"], ["verify" => false, 'cookies' => $jar]);
				
				if(is_string($response)) {
					$response["success"] = "success";
					$response["success_description"] = "login success";
					$response["scope"] = "login";
				}
				
				if(is_object($response)) {
					$SignUp = $response -> getBody() -> getContents();
					
					if(mb_stristr($response -> getBody() -> getContents(), "註冊") != false) {
						$response["success"] = "success";
						$response["success_description"] = "logout success";
						$response["scope"] = "logout";
					}
				}
			}
			else if($data["http_method"] == "POST") {
				$response = $client -> request($data["http_method"], $data["request_url"], [
					"verify" => false,
					'json' => ['email' => $data["account"], 'password' => $data["password"]],
					'cookies' => $jar,
					'headers' => ['x-csrf-token' => $this -> GetToken()];
				]);
			}
	
			else {
				$response["error"] = "failed";
				$response["error_description"] = "http_method is invalid";
				$response["scope"] = "http_method";
			}
			
			if(is_array($response))
				$response = json_encode($response);
			
			return $response;
		}
		
		private function GetToken() {
			$client = $this -> InitialClient();
			$response = $client -> createRequest('GET', 'https://www.dcard.tw/login', ["verify" => false, 'cookies' => $jar]);
			
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
	
			return $CsrfToken = $json_arr['app']['csrfToken'];
		}
		
		private function InitialClient() {
			$client = new Client();
			$jar = new \GuzzleHttp\Cookie\CookieJar();
			return $client;
		}
		
	}
?>
