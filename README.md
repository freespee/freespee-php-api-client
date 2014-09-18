# About

A PHP client class for the Freespee API

## API Documentation

https://developers.freespee.com/api



## Recommended: using composer

In composer.json, add

```
"freespee/freespee-php-api-client": "1.0.0"
```

to the require section

For more information, see https://getcomposer.org/



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
