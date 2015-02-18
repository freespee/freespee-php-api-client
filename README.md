# About

A PHP client class for the Freespee API

## API Documentation

http://support.freespee.com/hc/en-us/categories/preview?developers


## Recommended: using composer

In composer.json, add

```
"freespee/freespee-php-api-client": "1.1.2"
```

to the require section

For more information, see https://packagist.org/packages/freespee/freespee-php-api-client



## Using a git clone

```
git clone https://github.com/freespee/freespee-php-api-client.git
cd freespee-php-api-client
```

configure api account with information provided by Freespee:
```
cd settings
cp settings.php.default settings.php
nano settings.php
```

verify it's working:

```
cd <source root>
phpunit
```
