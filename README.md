Nines Bundles
=============

Some useful bundles.

Installation
------------

### Step 1: Download the package

Open your composer.json file and add the Nines Bundles repository.

```json
    "repositories": [
        {
            "type": "github",
            "url": "https://github.com/ubermichael/nines-bundles.git"
        }
    ],

```

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require ubermichael/nines 1.x-dev
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Configure and use them

Consult the documentation for each one.

Updating from Master
--------------------

The instructions for the master branch were to install the bundles as a git 
submodule. This was convenient at the time but not really sustainable in the
long term.

Step 1: Remove the submodule

```console
$ git submodule deinit src/Nines
$ git rm src/Nines
$ git commit -m "removed submodule"
$ rm -rf .git/modules/src/Nines
```

Step 2: Download the bundles as a composer package

Follow step 1 above.

Step 3: Adjust the config

Change the twig path for the NinesUtilBundle.

```yaml
# app/config/config.yml

twig:
    paths:
        '%kernel.project_dir%/vendor/ubermichael/Nines/UtilBundle/Resources/views': NinesUtilBundle
```
