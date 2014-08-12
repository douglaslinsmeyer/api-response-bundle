Symfony2 API Response Bundle
============================

1. Installation
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

2. Usage
-------------------------------

```php


/**
 * @Inject("acme.repository.document")
 *
 * @var DocumentRepository
 */
private $documentRepository;

/**
 * @Inject("dlinsmeyer_api.response_factory")
 *
 * @var ResponseFactory
 */
private $responseFactory;

//...

/**
 * Search for documents
 *
 * @Route("/api/v{version}/documents/search/{query}.{format}", name="acme_api_document_search")
 *
 * @param string $version
 * @param string $query
 * @param string $format e.g. "json"|"xml"|"yml"
 *
 * @return JsonResponse
 */
public function searchAction($version, $query, $format)
{
    $documents = $this->documentRepository->search($query);
    $response = $this->responseFactory->create(
        true,
        2000,
        'Search complete.',
        $documents,
        $version,
        null,
        $format
    );

    return $response;
}
    
```
