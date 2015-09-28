[![Build Status](https://travis-ci.org/davewwww/SimpleAccessor.svg)](https://travis-ci.org/davewwww/SimpleAccessor) [![Coverage Status](https://coveralls.io/repos/davewwww/SimpleAccessor/badge.svg)](https://coveralls.io/r/davewwww/SimpleAccessor) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/davewwww/SimpleAccessor/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/davewwww/SimpleAccessor/?branch=master)

SimpleAccessor
--------------
Simple property access to objects and arrays by using simple string notation

```php
SimpleAccessor::getValueFromPath($object, 'foo.bar.date');
```

Installation
------------
Installation with Composer
```yml
composer.phar require dwo/simple_accessor
```