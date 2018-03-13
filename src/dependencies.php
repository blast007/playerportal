<?php declare(strict_types=1);
$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Twig
$container['view'] = function ($c) {
    $settings = $c->get('settings')['view'];

    $view = new \Slim\Views\Twig($settings['template_path'], [
        'cache' => $settings['cache_path'] ?? false
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($c['router'], $basePath));

    $view['user'] = [
        'username' => $_SESSION['user']['username'] ?? false,
        'user_id' => $_SESSION['user']['user_id'] ?? false,
    ];

    // One time messages
    if (isset($_SESSION['flash_message'])) {
        $view['flash_message'] = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
    }

    return $view;
};

// pomm
$container['db'] = function($c) {
    $settings = $c->get('settings')['database'];
    $dsn = sprintf('pgsql://%s:%s@%s/%s', $settings['username'], $settings['password'], $settings['host'], $settings['database']);
    return (new \PommProject\Foundation\Pomm(['player_portal' =>
        [
            'dsn' => $dsn,
            'class:session_builder' => '\PommProject\ModelManager\SessionBuilder',
        ]
    ]))['player_portal'];
};

// Password
$container['password'] = function($c) {
    $settings = $c->get('settings')['passwords'];
    return new \App\Util\Password($settings);
};