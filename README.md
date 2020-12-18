# CacheRedis
ProcessWire interface to Redis memory cache

## Purpose
This module provides a simple interface to the [phpredis](https://github.com/phpredis/phpredis) library for caching contents in-memory. The functionality leans on [ProcessWire](https://processwire.com/)'s WireCache core library.

## Compatibility
Compatible with ProcessWire 3.x

## Status
Alpha, WiP, use with care

## Prerequisites
The phpredis extension must be installed.

## Usage

### Setup

After installation, you need to enable the module in the module settings by ticking the "Active" checkbox. The server and port are preset to 127.0.0.1 and 6379.

The configuration options support connecting via unix domain sockets and using TLS. You can optionally enable authentication.

### Basics

CacheRedis registers itself as a PW API variable with the name "redis", so you can access in all the usual ways:
```PHP
$var = $redis->get('mykey');
$var = wire('redis')->get('otherkey');
$var = $this->redis->get('thirdkey'); // only within Wire derived classes
```

### Methods

#### $redis->fetch($name, $expire, $func)

Retrieve a value from the cache by the given name. If $func is given and no cached entry was found, call $func and use its return value instead, saving that in the cache with the given expiry time.

Alternatively, you can pass an array of names to the method. In that case, an associative array of names and values is returned, with only those keys present that were found in the cache. If you do that, you must not pass a $func parameter.

#### $redis->fetchFor($ns, $name)

Retrieve a value from the cache by then given name in the given namespace.

Pass in '' (empty string) in $name to fetch all values in that namespace as an associative array.

#### $redis->store($name, $expire, $value)

Save the value in the cache with the given name as the key and the specified expire time. If $expire is not set, CacheRedis::expireDaily is assumed (24h).

#### $redis->storeFor($fn, $name, $expire, $value)

Save the value in the cache in the given namespace with the given name as the key and the specified expire time. If $expire is not set, CacheRedis::expireDaily is assumed (24h).

#### $redis->delete($name)

Deletes the entry with the given key name from the cache.

#### $redis->deleteFor($ns, $name, [$force])

Deletes the entry with the given key name in the given namespace from the cache.

Pass '' (empty string) in $name to delete all entries in the namespace.

Set $force to true to use the slower synchronous DEL behind the scenes.

#### $redis->flush()

Delete all entries from the cache.

#### $redis->renderFile($filename, $expire, _array_ $options)

This method behaves similar to ```$files->render()``` and actually delegates the file rendering to that method (when creating the cache). The important difference is that this method caches the output according to the given expiry value, rather than re-rendering the file on every call.

If there are any changes to the source file `$filename` the cache will be automatically re-created, regardless of what is specified for the `$expire` argument.

```PHP
// render primary nav from site/templates/partials/primary-nav.php
// and cache for 3600 seconds (1 hour)
echo $redis->renderFile('partials/primary-nav.php', 3600);
```

*Parameters for renderFile:*

- string `$filename`
  + Filename to render (typically PHP file)
  + Can be full path/file, or dir/file relative to current work directory (which is typically /site/templates/).
	+ If providing a file relative to current dir, it should not start with "/". 
	+ File must be somewhere within site/templates/, site/modules/ or wire/modules/, or provide your own `allowedPaths` option. 
	+ Please note that $filename receives API variables already (you don’t have to provide them).

- int|string `$expire`
	 - Specify one of the `CacheRedis::expire*` constants.
	 - Specify the time in seconds until expiry, up to 30 days.
	 - Specify the future date you want it to expire (any value above 2592000 will be treated as a unix timestamp).
	 - Specify `CacheRedis::expireNever` to prevent expiration.
	 - Specify `CacheRedis::expireSave` to expire when any page or template is saved.
	 - Specify a selector string. In that case, whenever a page is saved that matches the selector, the cache for the file is cleared.
	 - You can specify multiple selectors as an array. This can make sense with multiple selectors and/or if you want to specify selector expiry and a maximum lifetime.
	 - Omit for default value, which is `CacheRedis::expireDaily`. 

- array `$options`
  Accepts all options for the `WireFileTools::render()` method, plus these additional ones:
    - `name` (string): Optionally specify a unique name for this cache, otherwise $filename will be used as the unique name. (default='')
    - `vars` (array): Optional associative array of extra variables to send to template file. (default=[])
    - `allowedPaths` (array): Array of paths that are allowed (default is anywhere within templates, core modules and site modules)
    - `throwExceptions` (bool): Throw exceptions when fatal error occurs? (default=true)

*Return*

string|bool Rendered template file or boolean false on fatal error (and throwExceptions disabled)

## Changes

### 0.1.0

- Add namespace methods

## License

Licensed under Mozilla Public License v2. See file LICENSE in the repository for details.

## Credits

Ryan Cramer, creator of ProcessWire. Quite a bit of his well thought out code was taken more or less literally from his WireCache class to keep things compatible and keep from reinventing the wheel.
    
