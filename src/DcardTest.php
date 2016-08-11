<?php
	require "HttpRequest.php";
	require "DcardSdk.php";
	
	use Dcard\sdk\DcardSdk;
	
	class DcardTest extends PHPUnit_Framework_TestCase {
		/** @test */
		public function sdkTest() {
			$DcardSdk = new DcardSdk("your-account", "your-password");
			
			//login testing (success)
			$response = $this -> dcardLoginTest($DcardSdk);
			
			$response = json_decode($response, true);
			
			$this -> assertSame("login success", $response["success_description"]);
			
			//login testing (failed)
			$DcardSdk = new DcardSdk("12345678", "12345678");
			$response = $this -> dcardLoginTest($DcardSdk);
			
			$response = json_decode($response, true);
			
			$this -> assertSame("login failed", $response["error_description"]);
			
			$DcardSdk = new DcardSdk("your-account", "your-password");
			
			//logout testing
			$response = $this -> dcardLogoutTest($DcardSdk);
			$response = json_decode($response, true);
			
			$this -> assertSame("success", $response["success"]);
			
			//forums testing
			$response = $this -> getForumsTest($DcardSdk);
			$json = json_decode($response, true);
			
			$this -> assertSame("marvel", $json[0]["alias"]);
			
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
			
			//forum not found
			//e.g. https://www.dcard.tw/_api/forums/sex123/posts?popular=true
			$ForumName = "sex123";
			$IsPopular = "true";
			$IsBefore = false;
			$PostId = "";
			
			$response = $this -> getListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame("Forum not found", $json["message"]);
			
			//popular must be a boolean
			//e.g. https://www.dcard.tw/_api/forums/sex/posts?popular=true123
			$ForumName = "sex";
			$IsPopular = "true123";
			$IsBefore = false;
			$PostId = "";
			
			$response = $this -> getListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame("message", $json["field"]);
			
			//get notification testing (login)
			$response = $this -> getNotifyTest($DcardSdk);
			$json = json_decode($response, true);
			
			$this -> assertSame(true, empty($json["error"]));
			
			//get dcard testing (login)
			$this -> dcardLoginTest($DcardSdk);
			$response = $this -> getDcardTest($DcardSdk);
			$json = json_decode($response, true);
			
			$this -> assertSame(true, empty($json["error"]));
			
			//get me testing (login)
			$this -> dcardLoginTest($DcardSdk);
			$response = $this -> getMeTest($DcardSdk);
			$json = json_decode($response, true);
			
			$this -> assertSame(true, empty($json["error"]));
			
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
		
	}
?>
