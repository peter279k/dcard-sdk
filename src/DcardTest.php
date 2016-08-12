<?php
	require "HttpRequest.php";
	require "DcardSdk.php";
	
	use Dcard\sdk\DcardSdk;
	
	class DcardTest extends PHPUnit_Framework_TestCase {
		/** @test */
		public function sdkTest() {
			$DcardSdk = new DcardSdk("your account", "your password");
			
			$this -> doLoginTest($DcardSdk);
			
			$DcardSdk = new DcardSdk("your account", "your password");
			
			$this -> doLogoutTest($DcardSdk);
			
			$this -> doForumTest($DcardSdk);
			
			$this -> doContentTest($DcardSdk);
			
			$this -> doListTest($DcardSdk);
			
			$this -> doNotifyTest($DcardSdk);
			
			$this -> doCardTest($DcardSdk);
			
			$this -> doMeTest($DcardSdk);
			
			$this -> doAcceptTest($DcardSdk);
		}
		
		public function doLoginTest($DcardSdk) {
			//login testing (success)
			
			$response = $this -> dcardLoginTest($DcardSdk);
			
			$response = json_decode($response, true);
			
			$this -> assertSame("login success", $response["success_description"]);
			
			//login testing (failed)
			
			$DcardSdk = new DcardSdk("12345678", "12345678");
			$response = $this -> dcardLoginTest($DcardSdk);
			
			$response = json_decode($response, true);
			
			$this -> assertSame("login failed", $response["error_description"]);
		}
		
		public function doLogoutTest($DcardSdk) {
			//logout testing
			
			$response = $this -> dcardLogoutTest($DcardSdk);
			$response = json_decode($response, true);
			
			$this -> assertSame("success", $response["success"]);
		}
		
		public function doForumTest($DcardSdk) {
			//forums testing
			
			$response = $this -> getForumsTest($DcardSdk);
			$json = json_decode($response, true);
			
			$this -> assertSame("marvel", $json[0]["alias"]);
		}
		
		public function doContentTest($DcardSdk) {
			//get contents testing
			
			$PostId = "224506882";
			
			$response = $this -> getContentsTest($DcardSdk, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame($PostId, (string)$json["id"]);
			
			//post not found testing
			
			$PostId = "224506882ss";
			
			$response = $this -> getContentsTest($DcardSdk, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame("Post not found", $json["message"]);
		}
		
		public function doListTest($DcardSdk) {
			$this -> doPageTest($DcardSdk);
			
			$this -> doNoPopTest($DcardSdk);
			
			$this -> doForumNotFound($DcardSdk);
			
			$this -> doTypeTest($DcardSdk);
		}
		
		public function doNotifyTest($DcardSdk) {
			//get notification testing (login)
			
			$response = $this -> getNotifyTest($DcardSdk);
			$json = json_decode($response, true);
			
			$this -> assertSame(true, empty($json["error"]));
		}
		
		public function doCardTest($DcardSdk) {
			//get dcard testing (login)
			
			$response = $this -> getDcardTest($DcardSdk);
			$json = json_decode($response, true);
			
			$this -> assertSame(true, empty($json["error"]));
		}
		
		public function doMeTest($DcardSdk) {
			//get me testing (login)
			
			$response = $this -> getMeTest($DcardSdk);
			$json = json_decode($response, true);
			
			$this -> assertSame(true, empty($json["error"]));
		}
		
		public function doAcceptTest($DcardSdk) {
			//accept friends's invitation (bothAccept: true or false (default value))
			
			$message = "Hello World !";
			$response = $this -> sendAcceptTest($DcardSdk, $message);
			$json = json_decode($response, true);
			
			$this -> assertSame(0, (int)$json["bothAccept"]);
		}
		
		public function doNoPopTest($DcardSdk) {
			//get lists testing (no popular posts next page)
			
			$ForumName = "sex";
			$IsPopular = "false";
			$IsBefore = true;
			$PostId = "224506286";
			
			$response = $this -> getListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame("西斯", $json[0]["forumName"]);
			
			//get lists testing (no popular posts first page)
			
			$ForumName = "sex";
			$IsPopular = "false";
			$IsBefore = false;
			$PostId = "";
			
			$response = $this -> getListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame("西斯", $json[0]["forumName"]);
		}
		
		public function doForumNotFound($DcardSdk) {
			//forum not found
			//e.g. https://www.dcard.tw/_api/forums/sex123/posts?popular=true
			
			$ForumName = "sex123";
			$IsPopular = "true";
			$IsBefore = false;
			$PostId = "";
			
			$response = $this -> getListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame("Forum not found", $json["message"]);
		}
		
		public function doTypeTest($DcardSdk) {
			//popular must be a boolean
			//e.g. https://www.dcard.tw/_api/forums/sex/posts?popular=true123
			
			$ForumName = "sex";
			$IsPopular = "true123";
			$IsBefore = false;
			$PostId = "";
			
			$response = $this -> getListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame("popular", $json["field"]);
		}
		
		public function doPageTest($DcardSdk) {
			//get lists testing (next page)
			
			$ForumName = "sex";
			$IsPopular = "true";
			$IsBefore = true;
			$PostId = "224506286";
			
			$response = $this -> getListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame(0, count($json));
			
			//get lists testing (first page)
			
			$ForumName = "sex";
			$IsPopular = "true";
			$IsBefore = false;
			$PostId = "";
			
			$response = $this -> getListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame("西斯", $json[0]["forumName"]);
		}
		
		public function dcardLoginTest($DcardSdk) {
			return $DcardSdk -> dcardLogin();
		}
		
		public function dcardLogoutTest($DcardSdk) {
			return $DcardSdk -> dcardLogout();
		}
		
		public function getForumsTest($DcardSdk) {
			return $DcardSdk -> getForums();
		}
		
		public function getContentsTest($DcardSdk, $PostId) {
			return $DcardSdk -> getPostContents($PostId);
		}
		
		public function getListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId) {
			return $DcardSdk -> getPostLists($ForumName, $IsPopular, $IsBefore, $PostId);
		}
		
		public function getNotifyTest($DcardSdk) {
			//need login
			return $DcardSdk -> getNotification();
		}
		
		public function getDcardTest($DcardSdk) {
			//neeed login
			return $DcardSdk -> getDcard();
		}
		
		public function getMeTest($DcardSdk) {
			//need login
			return $DcardSdk -> getMe();
		}
		
		public function sendAcceptTest($DcardSdk, $message) {
			//need login
			return $DcardSdk -> sendAccept($message);
		}
		
	}
?>
