CakePHP GrayLog

This is a engine for Graylog centralized log management solution. Writes logs in your Graylog application server.

## Install
* Create folders `Log > Engine` if doesn't exists.
* Copy `src > Log > Engine > GrayLog.php` to your **Engine** folder.
* Add to the configuration file `GrayLog` config properties.

### Requirements
* PHP >= 7.1.x
* CakePHP >= 3.6.x

## Configure

Configure log in CakePHP `config > app.php` configuration file.

```php
use App\Log\Engine\GrayLog;

'Log' => [
    'debug' => [
        'className' => GrayLog::class, // or string: 'App\Log\Engine\GrayLog',
        'url' => env('LOG_DEBUG_URL', null),
        'scopes' => false,
        'levels' => ['notice', 'info', 'debug']
    ]
],
```
## Example

Log something from yout project.

```php
use 'Cake\Log\Log;

Log::write('debug', 'Something did not work');
// or
Log::debug('Something did not work!');
// or from controller
$this->log('Something did not work!', 'debug');
```

Enjoy ;)
