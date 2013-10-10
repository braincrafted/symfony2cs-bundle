BcSymfony2CodingStandardBundle
==============================

Sadly [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) does not contain the [Symfony2 coding standard](https://github.com/opensky/Symfony2-coding-standard) and is also not extensible. When you want to install PHP_CodeSniffer using Composer you have to install the coding standard manually whenever PHP_CodeSniffer is updated. Until now.


Author
------

- [Florian Eckerstorfer](http://florian.ec)


Compatiblity
------------

<table>
  <tr>
    <th>BcSymfony2CodingStandardBundle</th><th>Symfony</th>
  </tr>
  <tr>
    <td>0.1.*</td><td>2.3.*</td>
  </tr>
</table>


Installation
------------

First of all you have to add the bundle to your `composer.json`:

    {
        "require": {
            "braincrafted/symfony2cs-bundle": "0.1.*"
        }
    }

And then you have to add the bundle to your `AppKernel.php`.

    // AppKernel.php

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...
                new Bc\Bundle\Symfony2CodingStandardBundle\BcSymfony2CodingStandardBundle(),
            );

            // ...

            return $bundles;
        }
    }

Now you can execute the `bc:symfony2cs:install` command to install coding standard:

    php app/console bc:symfony2cs:install

However, things get even better if you add the script handler that is included in the bundle to the `post-install-cmd` and `post-update-cmd` sections in your `composer.json`:

    ...
    "scripts": {
        "post-install-cmd": [
            "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::buildBootstrap",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::clearCache",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installAssets",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installRequirementsFile",
            "Bc\\Bundle\\Symfony2CodingStandardBundle\\Composer\\ScriptHandler::installSymfony2CodingStandards"
        ],
        "post-update-cmd": [
            "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::buildBootstrap",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::clearCache",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installAssets",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installRequirementsFile",
            "Bc\\Bundle\\Symfony2CodingStandardBundle\\Composer\\ScriptHandler::installSymfony2CodingStandards"
        ]
    },
    ...

_The `composer.json` above is based on [Symfony 2.3.*](https://github.com/symfony/symfony-standard/blob/2.3/composer.json)._


Usage
-----

When you add the script handler to the `post-install-cmd` and `post-update-cmd` sections of your `composer.json` the bundle will install or update the coding standard whenever you run `composer install` and `composer update`.

You can now use the Symfony2 coding standard when you run PHP_CodeSniffer:

    ./bin/phpcs --standard=Symfony2 ./src/


Error handling
--------------

If you should encounter problem, add the `--verbose` option to the command to view the output of the executed commands.

    php app/console bc:symfony2cs:install --verbose


License
-------

The bundle is licensed under The MIT License. See the `LICENSE` file for more information.
