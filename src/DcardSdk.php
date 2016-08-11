<?php
	namespace Dcard\sdk;
	
	class DcardSdk {
		
		public function __construct($account, $password) {
			$this -> data = array();
			$this -> account = $account;
			$this -> password = $password;
		}
		
		public function dcardLogin() {
			$this -> data["account"] = $this -> account;
			$this -> data["password"] = $this -> password;
			$this -> data["http_method"] = "POST";
			$this -> data["request_url"] = "https://www.dcard.tw/_api/sessions";
			
			return $this -> sendHttpRequest();
		}
		
		public function dcardLogout() {
			$this -> data["http_method"] = "GET";
			$this -> data["request_url"] = "https://www.dcard.tw/logout";
			
			return $this -> sendHttpRequest();
		}
		
		public function getForums() {
			$this -> data["http_method"] = "GET";
			$this -> data["request_url"] = "https://www.dcard.tw/_api/forums";
			
			return $this -> sendHttpRequest();
		}

		public function getPostContents($PostId) {
			$this -> data["http_method"] = "GET";
			$PostId = htmlentities($PostId);
			$this -> data["request_url"] = "https://www.dcard.tw/_api/posts/" . $PostId;
			
			return $this -> sendHttpRequest();
		}
		
		public function getPostLists($ForumName, $IsPopular, $IsBefore, $PostId) {
			$this -> data["http_method"] = "GET";
			
			$BefStr = "";
			
			if($IsBefore === true) {
				$BefStr .= "&before=" . $PostId;
			}
			
			$this -> data["request_url"] = "https://www.dcard.tw/_api/forums/" . $ForumName . "/posts?popular=" . $IsPopular . $BefStr;
			
			return $this -> sendHttpRequest();
		}
		
		public function getNotification() {
			//need to login and have cookies
			$this -> data["http_method"] = "GET";
			
			$this -> data["request_url"] = "https://www.dcard.tw/_api/notifications";
			
			$this -> data["account"] = $this -> account;
			
			$this -> data["password"] = $this -> password;
			
			return $this -> sendHttpRequest();
		}
		
		public function getDcard() {
			//need to login and have cookie
			$this -> data["http_method"] = "GET";
			
			$this -> data["request_url"] = "https://www.dcard.tw/_api/dcard";
			
			$this -> data["account"] = $this -> account;
			
			$this -> data["password"] = $this -> password;

			return $this -> sendHttpRequest();
		}
		
		public function getMe() {
			//need to login and have cookie
			$this -> data["http_method"] = "GET";
			
			$this -> data["request_url"] = "https://www.dcard.tw/_api/me";
			
			$this -> data["account"] = $this -> account;
			
			$this -> data["password"] = $this -> password;
			
			return $this -> sendHttpRequest();
		}
		
		private function sendHttpRequest() {
			$request = new HttpRequest();
			$response = $request -> send($this -> data);
			
			return $response;
		}
		
	}
?>
