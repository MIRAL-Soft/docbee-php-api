# docbee-php-api
This Project is to use the docbee API with PHP.

# How to use
You can download the Package over composer with following line in composer File:

```
"require": {
    "php": ">=7.4.0",<br>
    "miralsoft/docbee-api": ">=v1"
}
```

# Configuration
The configuration have to been set in your PHP-Project. You must define 2 constants like this:

```
use miralsoft\docbee\api\Config;

Config::$URI = 'https://xxx.weclapp.com/webapp/api/v1/';
Config::$TOKEN = 'xxx';
```

Replace the xxx with your own data.