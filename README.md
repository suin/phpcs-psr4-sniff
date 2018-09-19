# suin/phpcs-psr4-sniff

A custom [PHP Code Sniffer] sniff to help to find classes that is not compliant with [PSR-4 Autoloader].

[PHP Code Sniffer]: https://github.com/squizlabs/PHP_CodeSniffer
[PSR-4 Autoloader]: https://www.php-fig.org/psr/psr-4/

## Demo

![](tests/demo/demo.png)

Please visit [./tests/demo](/tests/demo), if you would like to try this sniff.

```
# after clone this repository
composer install
vendor/bin/phpcs --standard=tests/demo/phpcs.xml --report=code
```

## Features

### Classes, interfaces and traits

This sniff covers not only classes but also interfaces and traits.

### Namespaces and class names

This sniff checks whether both of namespaces and class names match PSR-4 project structure.

### Configuration free

As this sniff respects `composer.json` [autoloading configuration], you don't have to declare mapping between namespace prefixes and base directories.

[autoloading configuration]: https://getcomposer.org/doc/04-schema.md#psr-4

## Similar packages

### Psr4Fixer of PHP-CS-Fixer

[Psr4Fixer] checkes if class names should match the file name and fixes the class names if its don't correspond to the file names. It doesn't check the namespaces.

In contrast, suin/phpcs-psr4-sniff checks not only class names but also namespaces.

[Psr4Fixer]:https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/src/Fixer/Basic/Psr4Fixer.php

### SlevomatCodingStandard.Files.TypeNameMatchesFileName

[SlevomatCodingStandard.Files.TypeNameMatchesFileName] is a custom PHP Code Sniffer sniff to check whether namespaces and class names follow PSR-0/PSR-4 project structure.

[SlevomatCodingStandard.Files.TypeNameMatchesFileName]: https://github.com/slevomat/coding-standard#slevomatcodingstandardfilestypenamematchesfilename

This sniff is similar to suin/phpcs-psr4-sniff, but it needs explicit configuration about the PSR-0/PSR-4 project structure. suin/phpcs-psr4-sniff takes implicit way as it follows the autoloding configuration of Composer.

## Installation

```
composer require --dev suin/phpcs-psr4-sniff
```

## Usage

At first, create a PHPCS ruleset XML (phpcs.xml.dist or phpcs.xml) file in the root of your project.

```xml
<?xml version="1.0"?>
<ruleset name="My Project">
    <!-- Specify directory that composer.json is placed. Usually it would be
    project root directory. -->
    <arg name="basepath" value="."/>

    <!-- Relative path to your ruleset.xml -->
    <rule ref="vendor/suin/phpcs-psr4-sniff/src/Suin"/>

    <!-- Optional: If you have to specify composer.json path, please add
    following section. -->
    <rule ref="Suin.Classes.PSR4">
        <properties>
            <!-- composerJsonPath must be relative path to "basepath" -->
            <property name="composerJsonPath" value="sub-dir/composer.json"/>
        </properties>
    </rule>
</ruleset>
```

Then run it with the command:

```
vendor/bin/phpcs src
```

## Changelog

Please see [CHANGELOG](https://github.com/suin/php/blob/master/CHANGELOG.md) for more details.

## Contributing

Send [issue](https://github.com/suin/php/issues) or [pull-request](https://github.com/suin/php/pulls) to main repository.
