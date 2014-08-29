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
 * @Inject("api_response_builder")
 *
 * @var ResponseBuilderInterface
 */
private $responseBuilder;

//...

/**
 * Search for documents
 *
 * @Route("/api/v{version}/documents/search/{query}.{_format}", name="acme_api_document_search")
 *
 * @param string $version
 * @param string $query
 *
 * @return JsonResponse
 */
public function searchAction($version, $query)
{
    $documents = $this->documentRepository->search($query);
    $this->responseBuilder->setVersion($version)
                          ->setFormat($this->getRequest()->getRequestFormat());

    if($someLogicCondition) {
        $this->responseBuilder->setSuccess(true)
                              ->setData($myLogicData);
    } else {
        $errors = $this->getErrors();
        $this->responseBuilder
            ->setSuccess(false)
            ->setMessage("Logic condition failed")
            ->setErrors($errors);
    }

    return $this->responseBuilder->buildResponse();

    return $response;
}

```
