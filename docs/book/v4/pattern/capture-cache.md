# CaptureCache

The `CaptureCache` pattern is useful for generating static resources to return
via HTTP request. When used in such a fashion, the web server needs to be
configured to run a PHP script generating the requested resource so that
subsequent requests for the same resource can be shipped without calling PHP
again.

This pattern comes with basic logic for managing generated resources.

## Quick Start

For use with an Apache 404 handler extend the Apache configuration, e.g.
`.htdocs`:

```apacheconf
ErrorDocument 404 /index.php
```

And add the cache to the related application script, e.g. `index.php`:

```php
use Laminas\Cache\Pattern\CaptureCache;
use Laminas\Cache\Pattern\PatternOptions;

$capture = new CaptureCache(
    new PatternOptions([
        'public_dir' => __DIR__,
    ])
);

// Start capturing all output, excluding headers, and write to the public
// directory:
$capture->start();

// Don't forget to change the HTTP response code
header('Status: 200', true, 200);

// do stuff to dynamically generate output
```

## Configuration Options

Option | Data Type | Default Value | Description
------ | --------- | ------------- | -----------
`public_dir` | `string` | none | Location of the public web root directory in which to write output.
`index_filename` | `string` | "index.html" | The name of the index file if only a directory was requested.
`file_locking` | `bool` | `true` | Whether or not to lock output files when writing.
`file_permission` | `int\|false` | `0600` (`false` on Windows) | Default permissions for generated output files.
`dir_permission` | `int\|false` | `0700` (`false` on Windows) | Default permissions for generated output directories.
`umask` | `int\|false` | `false` | Whether or not to umask generated output files / directories.

## Examples

### Scaling Images in the Web Root

Using the following Apache 404 configuration:

```apacheconf
# .htdocs
ErrorDocument 404 /index.php
```

Use the following script:

```php
// index.php
use Laminas\Cache\Pattern\CaptureCache;
use Laminas\Cache\Pattern\PatternOptions;

$capture = new CaptureCache(
    new PatternOptions([
        'public_dir' => __DIR__,
    ])
);
```

## Available Methods

In addition to the methods exposed in `PatternInterface`, this implementation
exposes the following methods.

```php
namespace Laminas\Cache\Pattern;

use Laminas\Cache\Exception;

class CaptureCache extends AbstractPattern
{
    /**
     * Starts capturing.
     */
    public function start(string|null $pageId = null): void;

    /**
     * Write a page to the requested path.
     *
     * @throws Exception\LogicException
     */
    public function set(string $content, string|null $pageId = null): void;

    /**
     * Retrieve a generated page from the cache.
     *
     * @throws Exception\LogicException
     * @throws Exception\RuntimeException
     */
    public function get(string|null $pageId = null): string|null;

    /**
     * Check if a cache exists for the given page.
     *
     * @throws Exception\LogicException
     * @return bool
     */
    public function has(string|null $pageId = null): bool;

    /**
     * Remove a page from the cache.
     *
     * @throws Exception\LogicException
     * @throws Exception\RuntimeException
     */
    public function remove(string|null $pageId = null): bool;

    /**
     * Clear cached pages that match the specified glob pattern.
     *
     * @throws Exception\LogicException
     */
    public function clearByGlob(string $pattern = '**'): void;

    /**
     * Returns the generated file name.
     */
    public function getFilename(string|null $pageId = null): string;
}
```
