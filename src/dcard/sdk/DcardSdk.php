<?php
	namespace Dcard\sdk;
	
	class DcardSdk {
		
		public function __construct() {
			$this -> data = array();
		}
		
		public function DcardLogin($account, $password) {
			$this -> data["account"] = $account;
			$this -> data["password"] = $password;
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
			
			$popular = null;
			
			$BefStr = "";
			
			if($IsBefore === true) {
				$BefStr .= "&before=" . $PostId;
			}
			
			if($IsPopular === true) {
				$popular = true;
			}
			else {
				$popular = false;
			}
			
			$this -> data["request_url"] = "https://www.dcard.tw/_api/forums/" . $ForumName . "/posts?popular=" . $popular . $BefStr;
			
			return $this -> SendHttpRequest();
		}
		
		public function GetNotification() {
			//need to login and have cookies
			$this -> data["http_method"] = "GET";
			
			$this -> data["request_url"] = "https://www.dcard.tw/_api/notifications";
			
			return $this -> SendHttpRequest();
		}
		
		public function GetDcard() {
			//need to login and have cookie
			$this -> data["http_method"] = "GET";
			
			$this -> data["request_url"] = "https://www.dcard.tw/_api/dcard";
			
			return $this -> SendHttpRequest();
		}
		
		public function GetMe() {
			//need to login and have cookie
			$this -> data["http_method"] = "GET";
			
			$this -> data["request_url"] = "https://www.dcard.tw/_api/me";
			
			return $this -> SendHttpRequest();
		}
		
		private function SendHttpRequest() {
			$response = HttpRequest::send($this -> data);
			
			return $response;
		}
		
	}
?>