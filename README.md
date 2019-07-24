Nines Bundles
=============

Some useful bundles.

Versions and Branches
---------------------

The 2.x branch uses TinyMCE as a text editor. The 1.x branch is set up for 
CkEditor. If you need one or the other editor make sure you pick the right 
branch. The poorly-named "master" branch is a development thing meant
for use as a git submodule. It's probably out of date.

These instructions are for the 2.x branch.

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
$ composer require ubermichael/nines 2.x-dev
   ```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Configure and use them

Consult the documentation for each one.

Updating from 1.x
-----------------

```shell
composer require phpunit/phpunit ^8.2
composer require ubermichael/nines 2.x-dev
composer remove friendsofsymfony/ckeditor-bundle
```

Remove the CkEditor configuration.

```yaml

```

Remove the CkEditor bundle from AppKernel.php and replace it with the NinesEditorBundle.

```diff
-            new FOS\CKEditorBundle\FOSCKEditorBundle(),
+            new Nines\EditorBundle\NinesEditorBundle(),
```

Add TinyMCE as a Bower dependency for your project.

```bash
bower install --save "tinymce-dist#^5.0.2"
```

Updating from Master
--------------------

The instructions for the master branch were to install the bundles as a git 
submodule. This was convenient at the time but not really sustainable in the
long term.

### Step 1: Remove the submodule

```console
$ git submodule deinit src/Nines
$ git rm src/Nines
$ git commit -m "removed submodule"
$ rm -rf .git/modules/src/Nines
```

### Step 2: Remove FOSCkEditorBundle from AppKernel.php

```php
# app/AppKernel.php

# remove this line
 new FOS\CKEditorBundle\FOSCKEditorBundle(),
```

### Step 3: Download the bundles as a composer package

Follow step 1 above.

### Step 3: Adjust the config

Change the twig path for the NinesUtilBundle.

```yaml
# app/config/config.yml

twig:
    paths:
        '%kernel.project_dir%/vendor/ubermichael/nines/UtilBundle/Resources/views': NinesUtilBundle
```

Add the image upload directory parameter.

```yaml
# app/config/paramters.yml and app/config/paramters.yml.dist

paramters:
    nines.editor.upload_dir: web/tinymce
```

That should be it.

