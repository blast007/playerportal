<?php declare(strict_types=1);
/*
 * BZFlag Player Portal provides an interface for managing BZFlag
 * organizations, groups, and hosting keys.
 * Copyright (C) 2018  BZFlag & Associates
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

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

// Pomm
$container['db'] = function ($c) {
    $settings = $c->get('settings')['database'];
    $dsn = sprintf('pgsql://%s:%s@%s/%s', $settings['username'], $settings['password'], $settings['host'], $settings['database']);
    return (new \PommProject\Foundation\Pomm(['player_portal' =>
        [
            'dsn' => $dsn,
            'class:session_builder' => '\App\Model\MySessionBuilder',
        ]
    ]))['player_portal'];
};

// Password
$container['password'] = function ($c) {
    $settings = $c->get('settings')['passwords'];
    return new \App\Util\Password($settings);
};

// Flash messages
$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages;
};

// CSRF
$container['csrf'] = function ($c) {
    return (new \Slim\Csrf\Guard())->setPersistentTokenMode(true);
};
