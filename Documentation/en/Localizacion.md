# Location

## Introduction

Creative **`Lang`** Provides a convenient way to retrieve text strings in multiple languages, allowing you to easily support multiple languages within your application.

## Language files
Language strings are stored in files within the directory **`application/langs`**. Within this directory there should be a subdirectory for each language supported by the application.

```
/application
    /langs
        /en
            messages.php
        /es
            messages.php
```

## Example of language file

Language files simply return an array of strings with a key. For example:

```php
<?php

return [
    'welcome' => 'Welcome to my application'
];
```

## Change default language

The default language for your application is stored in the configuration file **`application/config/app.php`**.

```php
<?php

return (object) [   
    'lang' => 'en'
];

```

## Basic Usage

### Retrieving lines from a language file

The first segment of the string passed to the get method is the name of the language file and the second is the name of the line to retrieve.

```php
echo Lang::get ('messages.welcome');
```

### Line Replacements

You can also define patterns to be replaced on the lines of your language:

```php
'welcome' => 'Welcome, :name',
```

Then pass a second argument from method overrides Lang::get:

```php
echo Lang::get('messages.welcome', ['name' => 'Matias']);
```

## Pluralization

Pluralization is a complex problem, since different languages have a variety of complex rules for pluralization. You can easily manage this in your language files. By using a character "*pipe*", Can separate the singular and plural forms of a chain:

```php
'apples' => 'There is one apple|There are many apples|There are some apples',
```

```php
echo Lang::get('messages.apples', 2);
```
The second parameter (2), Indicates which of the strings will be selected. The result of the example would be:
```
There are some apples
```

Another way is to use a pattern to be replaced

```php
'apples' => 'There are {0} apples',
```

```php
echo Lang::get('messages.apples', 100);
```
The second parameter (100), Will be the number by which the pattern will be replaced *{0}*. The result of the example would be:
```
There are 100 apples
```
