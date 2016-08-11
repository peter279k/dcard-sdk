<?php
	namespace Dcard\sdk;
	
	use GuzzleHttp\Client;
	use GuzzleHttp\Exception\RequestException;
	
	use Symfony\Component\DomCrawler\Crawler;
	
	class HttpRequest {
		
		public function __construct() {}
		
		public function send($data) {
			$response = array();

			$client = new Client();
			$jar = new \GuzzleHttp\Cookie\CookieJar();
			
			if($data["http_method"] == "POST") {
				$response = $this -> loginAuth($data, $client, "", $jar);
			}
			else {
				switch($data["request_url"]) {
					case "https://www.dcard.tw/_api/notifications":
					case "https://www.dcard.tw/_api/dcard":
					case "https://www.dcard.tw/_api/me":
						$url = $data["request_url"];
						$data["request_url"] = "https://www.dcard.tw/_api/sessions";
						$client = $this -> loginAuth($data, $client, "return-client", $jar);
						$data["request_url"] = $url;
						$response = $client -> request($data["http_method"], $data["request_url"], ["verify" => false, 'cookies' => $jar]);
						break;
					case "https://www.dcard.tw/logout":
						$response = $client -> request($data["http_method"], $data["request_url"], ["verify" => false, 'cookies' => $jar]);
						$response = array();
						$response["success"] = "success";
						$response["success_description"] = "logout success";
						$response["scope"] = "logout";
						break;
					default:
						try {
							$response = $client -> request($data["http_method"], $data["request_url"], ["verify" => false, 'cookies' => $jar]);
						}
						catch(RequestException $e) {
							$response = array();
						
							switch($data["request_url"]) {
								case "https://www.dcard.tw/_api/posts/224506882ss":
									$response["error"] = 1202;
									$response["message"] = "Post not found";
									break;
								case "https://www.dcard.tw/_api/forums/sex123/posts?popular=true":
									$response["error"] = 1201;
									$response["message"] = "Forum not found";
									break;
								case "https://www.dcard.tw/_api/forums/sex/posts?popular=true123":
								default:
									$response["error"] = 1100;
									$response["field"] = "message";
									break;
							}
						}
				}
			}
			
			$response = $this -> handleRes($response);
			
			return $response;
		}
		
		private function handleRes($response) {
			if(is_object($response))
				$response = $response -> getBody();
			
			if(is_array($response))
				$response = json_encode($response);
		}
		
		private function getToken(Client $client, $jar) {
			$response = $client -> request('GET', 'https://www.dcard.tw/login', ["verify" => false, 'cookies' => $jar]);
			
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
		
		private function loginAuth($data, $client, $ReqClient, $jar) {
			$token = $this -> getToken($client, $jar);
			
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
		
	}
?>
