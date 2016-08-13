# dcard-sdk
Help you to access easily the internal and official HTTP Dcard api.
[![Latest Stable Version](https://poser.pugx.org/dcard/sdk/v/stable)](https://packagist.org/packages/dcard/sdk)
[![Total Downloads](https://poser.pugx.org/dcard/sdk/downloads)](https://packagist.org/packages/dcard/sdk)
[![Latest Unstable Version](https://poser.pugx.org/dcard/sdk/v/unstable)](https://packagist.org/packages/dcard/sdk)
[![License](https://poser.pugx.org/dcard/sdk/license)](https://packagist.org/packages/dcard/sdk)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/e15c18a2-6ae4-44d0-ab03-81b810f43768/big.png)](https://insight.sensiolabs.com/projects/e15c18a2-6ae4-44d0-ab03-81b810f43768)

# Dcard HTTP API implementation
We implement the following API methods

The base url is https://www.dcard.tw/
	
| Request url|description|HTTP method|response format|the method|
|-------------|-------------|-------------|------------|------------|
| /_api/me | get account  information| GET | json | getMe |
| /_api/forums | get forum lists | GET | json | getForums |
| /_api/dcard | get today "Dcard" | GET | json | getDcard |
| /_api/notifications | get your notifications | GET | json | getNotification |
| /_api/posts/{post-id} | get specified post contents  | GET | json | getPostContents |
| /_api/forums/posts?popular={true/false}&before={post-id} | get specified forums's post lists  | GET | json | getPostLists |
| /_api/sessions | To login the Dcard Account | POST | json | dcardLogin |
| /_api/dcard/accept | Invite friends | POST | json | sendAccept |
| /logout | logout from the Dcard | GET | json | dcardLogout |

If you have to call other api methods, please open issue and let me know your requirement

# Usage
```php

//dowloading the composer.phar firstly
curl -sS https://getcomposer.org/installer | php

//using composer.phar to install the dcard-sdk
php composer.phar require dcard/sdk
```

# Sample code
```php
require "vendor/autoload.php";
	
use Dcard\sdk\DcardSdk;
	
$dcard = new DcardSdk("your account", "your password");

//login

$response = $dcard -> dcardLogin();

//get me

$response = $dcard -> getMe();

//get forums

$response = $dcard -> getForums();

//get "dcard"

$response = $dcard -> getDcard();

//get your account notifications

$response = $dcard -> getNotification();

//get specified post

$response = $dcard -> getPostContents("your-post-id");

//invite friends

$response = $dcard -> sendAccept("your-first-message");

//get specified forum's post lists
/*
	@param(type: string) $ForumName: the forum English name.
	@param(type: string) $IsPopular: the post whether is popular in forum or not and the value is true or false. 
	@param(type: boolean) $IsBefore: the post whether is the first page or specified page.
	@param(type: string) $PostId: the $IsBefore set true and the specified post id will get specified page.
	the $IsBefore set false and the specified post id will get specified page.
*/

$response = $dcard -> getPostLists($ForumName, $IsPopular, $IsBefore, $PostId);

//Dcard logout

$response = $dcard -> dcardLogout();

```

# Unit Testing
I use PHPUnit to test the package.

Here is the steps to test the package.

## Step 1
clone the project in master branch.

## Step 2
manually download the PHPUnit.phar.

## Step 3
download the composer.phar.

## Step 4
do ```php composer.phar install ``` in project root folder.

## Step 5
create the folder which name is report in project root folder.

## Step 6
In DcardTest.php, replace the account and password on line 11 and 28.

## Step 7
run testing: ```php phpunit.phar src/dcard/sdk/DcardTest.php --coverage-html report/```

Here is the testing result.

![Alt text](http://i.imgur.com/QmIw3Ew.png)

# Change log
## 2016/08/13
1. add invite friend 新增寄送邀請功能
	
# Related project
Dcard-API is developed by Node.js: [https://github.com/Larry850806/Dcard-API](Dcard-API)
