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

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->map(['get', 'post'], '/login', 'App\Controller\Authentication:login')->setName('login');
$app->get('/logout', 'App\Controller\Authentication:logout')->setName('logout');

$app->group('/organizations', function () {
    $this->get('', 'App\Controller\Organizations:index')->setName('organizations');
})->add(new \App\Middleware\ACL([
    'require_authentication' => true
]));

$app->get('/', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'index.twig');
})->setName('home');
