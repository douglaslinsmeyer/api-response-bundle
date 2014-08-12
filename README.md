Installation
============

1. Using Composer (recommended)
-------------------------------

To install the API bundle with Composer just add the following to your
`composer.json` file:

```js

// composer.json
{
    // ...
    require: {
        // ...
        "dlinsmeyer/api-response-bundle": "dev-master"
    }
}

```
    
```bash

$ php composer.phar update

```
    
Now, Composer will automatically download all required files, and install them
for you. All that is left to do is to update your ``AppKernel.php`` file, and
register the new bundle:

```php

<?php

// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new DLinsmeyer\Bundle\ApiBundle\DLinsmeyerApiBundle(),
    // ...
);

```
