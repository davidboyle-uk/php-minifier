# phpmin-php-minifier


PHPMin is an inline PHP minifier for web resources.  It lets you minify HTML4, HTML5, XHTML, CSS and JavaScript files in a single line of code.


The best bit is its free and open source under the BSD license.





## Installing PHPMin


Installing PHPMin should only take a minute. Here are your choices:

- Install via composer
```composer require -dev dbx123/php-minifier```
- Hit the 'Download Zip' button on GitHub.
https://github.com/dbx123/php-minifier

You can include all the necessary classes by simply including the php-minifier/phpmin.php file.
```php
require_once ("php-minifier/phpmin.php");
```



##  Using PHPMin


How to compress HTML:
```php
$minified_html = PHPMin\Minify::html($any_html);
```

This automatically compresses all inline scripts and stylesheets within the HTML document.
Apply a 'data-no-min' attribute to a script or style in your HTML markup to exclude it from minification.


How to compress CSS:
```php
$minified_css = PHPMin\Minify::css($any_css);
```


How to compress JS:
```php
$minified_js = PHPMin\Minify::js($any_js);
```


Spelling out out:
```php

require_once ("php-minifier/phpmin.php");

$html = file_get_contents("http://en.wikipedia.org/wiki/Minification_%28programming%29");
$minified_html = PHPMin\Minify::html($html);
// a 9.38% performance boost - in 3 lines of code!!

```


There are working examples in the /examples folder of the package.






## PHPMin Performance


- The compression ratio are typically 5-20% for HTML
- The compression ratio are typically 5-30% for CSS
- The compression ratio are typically 5-30% for JavaScript


Even highly optimized sites such as wikipedia.com, github.com and W3Schools.com could reduce the size of their HTML payload using PHPMin html compression. 





## Who Made PHPMin ?


The PHPMin project is currently maintained by David Boyle <https://github.com/dbx123> 
The Css compressor is built upon the CssMin package by Joe Scylla.
Some code also contributed by Rowan Beentje
The JavaScript compressor is built upon  Douglas Crockford's JSMin.





## PHPMin Purpose


PHPMin is here to reduce filesizes and page load times, parse css




 
## PHPMin Improvements


- We are relatively new to Composer - and welcome input on package improvements.
- Removing default values from html tags should reduce HTML file size
- Allowing HTML fragments rather than whole documents would be ideal.




## Unit Test Examples


./vendor/bin/phpunit --configuration phpunit.xml

./vendor/bin/phpunit --configuration phpunit.xml --coverage-html coverage-report

./vendor/bin/phpunit --configuration phpunit.xml test/CssMin/CssMinTest.php --coverage-html coverage-report

./vendor/bin/phpunit --configuration phpunit.xml --filter testCssConvertLevel3AtKeyframesMinifierFilterMinification --coverage-html coverage-report



./vendor/squizlabs/php_codesniffer/bin/phpcs --standard=PSR2 src


