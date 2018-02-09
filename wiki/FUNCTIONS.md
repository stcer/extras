# Function extras
> Collection of utilities similar to Underscore.js

## Methods

- [once](#once)
- [before](#before)
- [after](#before)
- [throttle](#throttle)
- [chain](#chain)


### once
Creates a version of the function that can only be called one time.
Repeated calls to the modified function will have no effect, returning the value
from the original call. Useful for initialization functions, instead of having to set a boolean flag
and then check it later.
[see also](http://underscorejs.org/#once).


##### Parameters
- `{callable|string|Closure} $callable` - source function

###### Syntax
```php
once($callable): callable
```

###### Example
```php
<?php
$func = Functions::once("myFunc");
$func(); // 1
$func(); // 1
$func(); // 1
```

### before
Creates a version of the function that can be called no more than count times.
The result of the last function call is memoized and returned when count has been reached.
[see also](http://underscorejs.org/#before).


##### Parameters
- `{callable|string|Closure} $callable` - source function
- `{int} $count` - count

###### Syntax
```php
before($callable, int $count): callable
```

###### Example
```php
<?php
$func = Functions::before("myFunc", 2);
$func(); // 1
$func(); // 2
$func(); // 2
```


### after
Creates a version of the function that will only be run after being called count times. Useful for grouping
asynchronous responses, where you want to be sure that all the async calls have finished, before proceeding.
[see also](http://underscorejs.org/#after).


##### Parameters
- `{callable|string|Closure} $callable` - source function
- `{int} $count` - count

###### Syntax
```php
after($callable, int $count): callable
```

###### Example
```php
<?php
$func = Functions::after("myFunc", 2);
$func(); // false
$func(); // false
$func(); // 1
```

### throttle
Creates and returns a new, throttled version of the passed function,
that, when invoked repeatedly, will only actually call the original function at most once per every
wait milliseconds. Useful for rate-limiting events that occur faster than you can keep up with.
[see also](http://underscorejs.org/#throttle).


##### Parameters
- `{callable|string|Closure} $callable` - source function
- `{int} $wait` - wait time in ms

###### Syntax
```php
throttle($callable, int $wait)
```

###### Example
```php
<?php
$func = Functions::throttle("myFunc", 20);
$func();
$func();
$func();
usleep(20000);
$func();
```


### chain
Returns a wrapped object. Calling methods on this object will continue to return wrapped objects until value is called.

##### Parameters
- `{string} $value` - source string

###### Syntax
```php
 chain(string $value): Strings
```

###### Example
```php
<?php
$res = Strings::chain( " 12345 " )
            ->replace("/1/", "5")
            ->replace("/2/", "5")
            ->trim()
            ->substr(1, 3)
            ->value();
echo $res; // "534"
```