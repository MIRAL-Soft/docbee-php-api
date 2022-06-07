# docbee-php-api
This Project is to use the docbee API with PHP.

# How to use
You can download the Package over composer with following line in composer File:

```
"require": {
    "php": ">=8.0.0",<br>
    "miralsoft/docbee-api": ">=v1"
}
```

# Configuration
To use the API you have to make a Config Object with the Options you need for docbee:

```
use miralsoft\docbee\api\Config;

// Generate conig with token
$config = new Config(APITOKEN);
```

Replace the APITOKEN with your own Token from docbee.

# Example
Here a little example to use the docbee api:

```
require_once '../vendor/autoload.php';

use miralsoft\docbee\api\Config;
use miralsoft\docbee\api\Customer;

// Generate conig with token
$config = new Config(APITOKEN);

// Generate the customer object
$customer = new Customer($config);

$result = $customer->get(); // Get all customers
```

For more examples you can look in the test-scripts.