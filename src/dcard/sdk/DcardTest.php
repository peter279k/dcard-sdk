<?php
	require "./HttpRequest.php";
	require "./DcardSdk.php";
	
	use Dcard\sdk\HttpRequest;
	use Dcard\sdk\DcardSdk;
	
	class DcardTest extends PHPUnit_Framework_TestCase {
		/** @test */
		public function SdkTest() {
			$DcardSdk = new DcardSdk();
			
			//login testing
			$response = $this -> DcardLoginTest($DcardSdk);
			$response = json_decode($response, true);
			
			$this -> assertSame("login success", $response["success_description"]);
			
			//logout testing
			$response = $this -> DcardLogoutTest($DcardSdk);
			
			$this -> assertSame(0, (int)strpos($response -> getBody() -> getContents(), "註冊"));
			
			//forums testing
			$response = $this -> GetForumsTest($DcardSdk);
			$json = json_decode($response, true);
			
			$this -> assertSame("marvel", $json[0]["alias"]);
			
			//get contents testing
			$PostId = "224506882";
			
			$response = $this -> GetContentsTest($DcardSdk, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame($PostId, $json["id"]);
			
			//post not found testing
			$PostId = "224506882ss";
			
			$response = $this -> GetContentsTest($DcardSdk, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame("Post not found", $json["message"]);
			
			//get lists testing (next page)
			$ForumName = "sex";
			$IsPopular = "true";
			$IsBefore = true;
			$PostId = "224506286";
			
			$response = $this -> GetListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame(0, count($json));
			
			//get lists testing (first page)
			$ForumName = "sex";
			$IsPopular = "true";
			$IsBefore = false;
			$PostId = "";
			
			$response = $this -> GetListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame("西斯", $json[0]["forumName"]);
			
			//get lists testing (no popular posts next page)
			$ForumName = "sex";
			$IsPopular = "false";
			$IsBefore = true;
			$PostId = "224506286";
			
			$response = $this -> GetListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame("西斯", $json[0]["forumName"]);
			
			//get lists testing (no popular posts first page)
			$ForumName = "sex";
			$IsPopular = "false";
			$IsBefore = false;
			$PostId = "";
			
			$response = $this -> GetListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame("西斯", $json[0]["forumName"]);
			
			//forum not found
			//e.g. https://www.dcard.tw/_api/forums/sex123/posts?popular=true
			$ForumName = "sex123";
			$IsPopular = "true";
			$IsBefore = false;
			$PostId = "";
			
			$response = $this -> GetListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame("Forum not found", $json["message"]);
			
			//popular must be a boolean
			//e.g. https://www.dcard.tw/_api/forums/sex/posts?popular=true123
			$ForumName = "sex";
			$IsPopular = "true123";
			$IsBefore = false;
			$PostId = "";
			
			$response = $this -> GetListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId);
			$json = json_decode($response, true);
			
			$this -> assertSame("message", $json["field"]);
			
			//get notification testing (no login)
			$this -> DcardLogoutTest($DcardSdk);
			$response = $this -> GetNotifyTest($DcardSdk);
			$json = json_decode($response, true);
			
			$this -> assertSame(true, empty($json["error"]));
			
			//get notification testing (login)
			$this -> DcardLoginTest($DcardSdk);
			$response = $this -> GetNotifyTest($DcardSdk);
			$json = json_decode($response, true);
			
			$this -> assertSame(false, empty($json["error"]));
			$this -> DcardLogoutTest($DcardSdk);
			
			//get dcard testing (no login)
			$this -> DcardLogoutTest($DcardSdk);
			$response = $this -> GetDcardTest($DcardSdk);
			$json = json_decode($response, true);
			
			$this -> assertSame(true, empty($json["error"]));
			
			//get dcard testing (login)
			$this -> DcardLoginTest($DcardSdk);
			$response = $this -> GetDcardTest($DcardSdk);
			$json = json_decode($response, true);
			
			$this -> assertSame(false, empty($json["error"]));
			
			//get me testing (no login)
			$this -> DcardLogoutTest($DcardSdk);
			$response = $this -> GetMeTest($DcardSdk);
			$json = json_decode($response, true);
			
			$this -> assertSame(true, empty($json["error"]));
			
			//get me testing (login)
			$this -> DcardLoginTest($DcardSdk);
			$response = $this -> GetMeTest($DcardSdk);
			$json = json_decode($response, true);
			
			$this -> assertSame(false, empty($json["error"]));
			
			
			
		}
		
		public function DcardLoginTest($DcardSdk) {
			$account = "your account";
			$password = "your password";
			
			return $DcardSdk -> DcardLogin($account, $password);
		}
		
		public function DcardLogoutTest($DcardSdk) {
			return $DcardSdk -> DcardLogout();
		}
		
		public function GetForumsTest($DcardSdk) {
			return $DcardSdk -> GetForums();
		}
		
		public function GetContentsTest($DcardSdk, $PostId) {
			return $DcardSdk -> GetPostContents($PostId);
		}
		
		public function GetListsTest($DcardSdk, $ForumName, $IsPopular, $IsBefore, $PostId) {
			return $DcardSdk -> GetPostLists($ForumName, $IsPopular, $IsBefore, $PostId);
		}
		
		public function GetNotifyTest($DcardSdk) {
			//need login
			return $DcardSdk -> GetNotification();
		}
		
		public function GetDcardTest($DcardSdk) {
			//neeed login
			return $DcardSdk -> GetDcard();
		}
		
		public function GetMeTest($DcardSdk) {
			//need login
			return $DcardSdk -> GetMe();
		}
		
	}
?>