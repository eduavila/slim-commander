# SlimCommander for Slim v4

A very simple structure for running CLI commands as part of your Slim Framework application v4.

This is not a console tool. It's just a parallel to the HTTP entry point into your application, 
enabling you to do things like create create scripts to be run as cronjobs or set up basic queue listeners.


## Usage 

Taking the structure of Slim-Skeleton as an example, your `public/index.php` does this:

```php
require __DIR__ . '/../vendor/autoload.php';

session_start();

use DI\ContainerBuilder;
use Slim\App;
use SlimFacades\Facade;

// Instantiate container 
// Container PHP-DI 
$containerBuilder = new ContainerBuilder();

// Definitions PHP-DI
$containerDefinitions = require __DIR__ .'/container.php';

$containerBuilder->addDefinitions($containerDefinitions);

$container = $containerBuilder->build();
$app = $container->get(App::class);

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';


// Run app
$app->run();
```

You need to create a new PHP script, similar to this, to serve as the entry point for your commands. 
It should be outside the `public` folder. Perhaps `src/cli.php`.

```php

use DI\ContainerBuilder;

// Instancia container 
// Container PHP-DI 
$containerBuilder = new ContainerBuilder();

// Configura PHP-DI
$containerDefinitions = require __DIR__ .'/../src/app/container.php';

$containerBuilder->addDefinitions($containerDefinitions);
$container = $containerBuilder->build();

$app = new \DrewM\SlimCommander\App($container);

// Definições de comandos cli.
require __DIR__ . '/commands.php';

// Run app
$app->run($argv);

```

Instead of routes, you define commands in e.g. `src/commands.php`.

```php
$app->command('HelloWorld', 'HelloWorld:greet', [
    'name',
]);
```

Arguments are:

1. Name of the command
2. The callback, defined in the same way as a regular Slim route callback
3. An array of expected argument names

In the above example, the first argument will be passed to the callback as `name`

Your callback gets the container passed to its constructor:

```php
class HelloWorld
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function greet($args)
    {
        echo "Hello " . $args['name'];
    }
}
```

Add it to your container, just as you would normally:

```php
$container['HelloWorld'] = function ($container) {
    return new \App\Commands\HelloWorld($container);
};
```

And then you'd execute it with `php src/cli.php HelloWorld Fred`