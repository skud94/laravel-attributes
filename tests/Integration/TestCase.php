<?php

declare(strict_types=1);
use Rinvex\Attributes\Models\Attribute;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'testing']);
        $this->loadLaravelMigrations('testing');
        $this->withFactories(__DIR__.'/Factories');

        // Registering the core type map
        Attribute::typeMap([
            'text' => \Rinvex\Attributes\Models\Type\Text::class,
            'bool' => \Rinvex\Attributes\Models\Type\Boolean::class,
            'integer' => \Rinvex\Attributes\Models\Type\Integer::class,
            'varchar' => \Rinvex\Attributes\Models\Type\Varchar::class,
            'datetime' => \Rinvex\Attributes\Models\Type\Datetime::class,
        ]);

        // Push your entity fully qualified namespace
        app('rinvex.attributes.entities')->push(User::class);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [Rinvex\Attributes\Providers\AttributesServiceProvider::class];
    }
}
