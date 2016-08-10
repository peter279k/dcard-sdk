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
curl -sS https://getcomposer.org/installer | /path/to/executable/php

//using composer.phar to install the dcard-sdk
php composer.phar require 
```

# Related project
Dcard-API is developed by Node.js: [https://github.com/Larry850806/Dcard-API](Dcard-API)
