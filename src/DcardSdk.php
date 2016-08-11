<?php
	namespace Dcard\sdk;
	
	class DcardSdk {
		
		public function __construct($account, $password) {
			$this -> data = array();
			$this -> account = $account;
			$this -> password = $password;
		}
		
		public function DcardLogin() {
			$this -> data["account"] = $this -> account;
			$this -> data["password"] = $this -> password;
			$this -> data["http_method"] = "POST";
			$this -> data["request_url"] = "https://www.dcard.tw/_api/sessions";
			
			return $this -> SendHttpRequest();
		}
		
		public function DcardLogout() {
			$this -> data["http_method"] = "GET";
			$this -> data["request_url"] = "https://www.dcard.tw/logout";
			
			return $this -> SendHttpRequest();
		}
		
		public function GetForums() {
			$this -> data["http_method"] = "GET";
			$this -> data["request_url"] = "https://www.dcard.tw/_api/forums";
			
			return $this -> SendHttpRequest();
		}

		public function GetPostContents($PostId) {
			$this -> data["http_method"] = "GET";
			$PostId = htmlentities($PostId);
			$this -> data["request_url"] = "https://www.dcard.tw/_api/posts/" . $PostId;
			
			return $this -> SendHttpRequest();
		}
		
		public function GetPostLists($ForumName, $IsPopular, $IsBefore, $PostId) {
			$this -> data["http_method"] = "GET";
			
			$BefStr = "";
			
			if($IsBefore === true) {
				$BefStr .= "&before=" . $PostId;
			}
			
			$this -> data["request_url"] = "https://www.dcard.tw/_api/forums/" . $ForumName . "/posts?popular=" . $IsPopular . $BefStr;
			
			return $this -> SendHttpRequest();
		}
		
		public function GetNotification() {
			//need to login and have cookies
			$this -> data["http_method"] = "GET";
			
			$this -> data["request_url"] = "https://www.dcard.tw/_api/notifications";
			
			$this -> data["account"] = $this -> account;
			
			$this -> data["password"] = $this -> password;
			
			return $this -> SendHttpRequest();
		}
		
		public function GetDcard() {
			//need to login and have cookie
			$this -> data["http_method"] = "GET";
			
			$this -> data["request_url"] = "https://www.dcard.tw/_api/dcard";
			
			$this -> data["account"] = $this -> account;
			
			$this -> data["password"] = $this -> password;

			return $this -> SendHttpRequest();
		}
		
		public function GetMe() {
			//need to login and have cookie
			$this -> data["http_method"] = "GET";
			
			$this -> data["request_url"] = "https://www.dcard.tw/_api/me";
			
			$this -> data["account"] = $this -> account;
			
			$this -> data["password"] = $this -> password;
			
			return $this -> SendHttpRequest();
		}
		
		private function SendHttpRequest() {
			$request = new HttpRequest();
			$response = $request -> send($this -> data);
			
			return $response;
		}
		
	}
?>
