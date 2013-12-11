BraincraftedSymfony2CSBundle
============================

Unfortunately [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) does not contain the [Symfony2 coding standard](https://github.com/opensky/Symfony2-coding-standard) and is not extensible. If you want to install PHP_CodeSniffer using Composer you have to install the coding standard manually everytime PHP_CodeSniffer is updated. Until now.


Author
------

- [Florian Eckerstorfer](http://florian.ec)


Compatiblity
------------

<table>
  <tr>
    <th>BraincraftedSymfony2CSBundle</th><th>Symfony</th>
  </tr>
  <tr>
    <td><code>0.1.*</code></td><td><code>2.3.*</code></td>
  </tr>
  <tr>
      <td><code>0.2.*</code></td><td><code>2.4.*</code></td>
  </tr>
  <tr>
    <td><code>master</code></td><td><code>2.4.*</code></td>
  </tr>
</table>


Installation
------------

First you have to add the bundle to your `composer.json`:

```json
{
    "require": {
        "braincrafted/symfony2cs-bundle": "dev-master"
    }
}
```

Next you have to add the bundle to your `AppKernel.php`:

```php
// AppKernel.php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Braincrafted\Bundle\Symfony2CSBundle\BraincraftedSymfony2CSBundle(),
        );

        // ...

        return $bundles;
    }
}
```

You can now execute the `braincrafted:symfony2cs:install` command to install the Symfony2 coding standard:

```bash
$ php app/console braincrafted:symfony2cs:install
```

However, things get better if you add the script handler that is included in the bundle to the `post-install-cmd` and `post-update-cmd` sections of your `composer.json`:

```json
    ...
    "scripts": {
        "post-install-cmd": [
            "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::buildBootstrap",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::clearCache",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installAssets",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installRequirementsFile",
            "Braincrafted\\Bundle\\Symfony2CSBundle\\Composer\\ScriptHandler::installSymfony2CS"
        ],
        "post-update-cmd": [
            "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::buildBootstrap",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::clearCache",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installAssets",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installRequirementsFile",
            "Braincrafted\\Bundle\\Symfony2CSBundle\\Composer\\ScriptHandler::installSymfony2CS"
        ]
    },
    ...
```

Usage
-----

If you add the script handler to the `post-install-cmd` and `post-update-cmd` sections of your `composer.json` the bundle will install or update the coding standard everytime you run `composer install` or `composer update`.

You can use the Symfony2 coding standard when you run PHP_CodeSniffer:

```bash
$ ./bin/phpcs --standard=Symfony2 ./src/
```

Error handling
--------------

If you should encounter problem add the `--verbose` option to the command to view the output of the executed commands.

```bash
$ php app/console braincrafted:symfony2cs:install --verbose
```

Changelog
---------

### Version 0.2 (2013-11-14)

- Changed namespace from `Bc` to `Braincrafted`
- Support for Symfony 2.4

License
-------

The bundle is licensed under The MIT License. See the `LICENSE` file for more information.


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/braincrafted/symfony2cs-bundle/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

