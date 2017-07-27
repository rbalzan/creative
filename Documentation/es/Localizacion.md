# Localización

## Introducción

Creative **`Lang`** proporciona una manera conveniente de recuperar cadenas de texto en varios idiomas, lo que le permite soportar fácilmente varios idiomas dentro de su aplicación.

## Archivos de idioma

Las cadenas de idiomas se almacenan en archivos dentro del directorio **`application/langs`**. Dentro de este directorio debe haber un subdirectorio para cada idioma soportado por la aplicación.

```
/application
    /langs
        /en
            messages.php
        /es
            messages.php
```

## Ejemplo del archivo de idioma

Los archivos de idioma simplemente devuelven una matriz de cadenas con clave. Por ejemplo:

```php
<?php

return [
    'welcome' => 'Welcome to my application'
];
```

## Cambiar el idioma predeterminado

El idioma predeterminado para su aplicación se almacena en el archivo de configuración **`application/config/app.php`**.

```php
<?php

return (object) [   
    'lang' => 'en'
];

```

## Uso básico

### Recuperación de líneas de un archivo de idioma

El primer segmento de la cadena pasada al método get es el nombre del archivo de idioma y el segundo es el nombre de la línea que se debe recuperar.

```php
echo Lang::get ('messages.welcome');
```
### Reemplazos en líneas

También puede definir patrones para ser reemplazados en las líneas de su idioma:

```php
'welcome' => 'Welcome, :name',
```

Luego, pase un segundo argumento de reemplazos al método Lang::get:

```php
echo Lang::get('messages.welcome', ['name' => 'Matias']);
```

## Pluralización

La pluralización es un problema complejo, ya que diferentes lenguajes tienen una variedad de reglas complejas para la pluralización. Puede administrar fácilmente esto en sus archivos de idioma. Mediante el uso de un carácter "*pipe*", puede separar las formas singular y plural de una cadena:

```php
'apples' => 'There is one apple|There are many apples|There are some apples',
```

```php
echo Lang::get('messages.apples', 2);
```
El segundo parametro (2), indica cual de las cadenas será seleccionada. El resultado del ejemplo sería:
```
There are some apples
```

Otra forma es usar un patron para ser reemplazado

```php
'apples' => 'There are {0} apples',
```

```php
echo Lang::get('messages.apples', 100);
```
El segundo parametro (100), será el número por el cual será reemplazado el patron *{0}*. El resultado del ejemplo sería:
```
There are 100 apples
```