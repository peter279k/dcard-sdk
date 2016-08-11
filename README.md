# dcard-sdk
Help you to access easily the internal and official HTTP Dcard api

# Dcard HTTP API implementation
We implement the following API methods

The base url is https://www.dcard.tw/
	
| Request url|description|HTTP method|response format|
|-------------|-------------|-------------|------------|
| /_api/me | get account  information| GET | json |
| /_api/forums | get forum lists | GET | json |
| /_api/dcard | get today "Dcard" | GET | json |
| /_api/notifications | get your notifications | GET | json |
| /_api/posts/{post-id} | get specified post contents  | GET | json |
| /_api/sessions | To login the Dcard Account | POST | json |

If you have to call other api methods, please open issue and let me know your requirement

# Usage
```php

//dowloading the composer.phar firstly
curl -sS https://getcomposer.org/installer | php

//using composer.phar to install the dcard-sdk
php composer.phar require 
```

# Testing
I use PHPUnit to test the package.

Here is the sample code to test the package.

	Step 1: clone the project in master branch.
	Step 2: manually download the PHPUnit.phar.
	Step 3: download the composer.phar.
	Step 4: do ```php php composer.phar install ``` in project root folder.
	Step 5: create the folder which name is report in project root folder.
	Step 6: In DcardTest.php, replace the account and password on line 11 and 28.
	Step 7: run testing: ```php php phpunit.phar src/dcard/sdk/DcardTest.php --coverage-html report/```

Here is the testing result.

![Alt text](http://i.imgur.com/xed4w9Q.png)
	
# Related project
Dcard-API is developed by Node.js: [https://github.com/Larry850806/Dcard-API](Dcard-API)
