
/**
 * Create a new Lumen application instance.
 *
 * @param  string|null  $basePath
 * @return void
 */
public function __construct($basePath = null)
{
    date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

    $this->basePath = $basePath;
    $this->bootstrapContainer();
    $this->registerErrorHandling();
}

<?php
return [
    'providers' => [
        Barryvdh\DomPDF\ServiceProvider::class,
    ],
    
    'aliases' => [
        'App' => Illuminate\Support\Facades\App::class,
        'PDF' => Barryvdh\DomPDF\Facade::class,
    ]
];