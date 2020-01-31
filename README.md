Nines Bundles
=============

Some useful bundles.

Instalation
-----------

A Flex recipe isn't included. All steps are required. 

1. Add the repository and package to your composer configuration

```json
{
    "require": {
        "ubermichael/nines": "3.x-dev"
    },
    "repositories": [
        {
            "type": "github",
            "url": "https://github.com/ubermichael/nines-bundles.git"
        }
    ],
}
```

2. Enable the bundles you need.

```php
// config/bundles.php

return [
    // ...
    Nines\BlogBundle\NinesBlogBundle::class => ['all' => true],
    Nines\DublinCoreBundle\NinesDublinCoreBundle::class => ['all' => true],
    Nines\EditorBundle\NinesEditorBundle::class => ['all' => true],
    Nines\FeedbackBundle\NinesFeedbackBundle::class => ['all' => true],
    Nines\UserBundle\NinesUserBundle::class => ['all' => true],
    Nines\UtilBundle\NinesUtilBundle::class => ['all' => true],
];
```

3. Copy the default configurations to `config/packages`

```shell script
cp vendor/ubermichael/nines/*/Resources/config/nines_*.yaml config/packages/
```

4. Update the routing information
