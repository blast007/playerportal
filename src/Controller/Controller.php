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

namespace App\Controller;

use App\Util\Password;
use Monolog\Logger;
use PommProject\ModelManager\Session;
use Slim\Container;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Slim\Http\Request;
use Slim\Views\Twig;

/**
 * @property Logger $logger
 * @property Twig $view
 * @property Password $password
 * @property Messages $flash
 * @property Session $db
 * @property Guard $csrf
 */
class Controller
{
    private $container;

    public function __construct(Container $c)
    {
        $this->container = $c;
    }

    public function assignCSRF(Request $request)
    {
        // Init the CSRF class
        $csrf = &$this->csrf;

        // Grab the key names
        $nameKey = $csrf->getTokenNameKey();
        $valueKey = $csrf->getTokenValueKey();

        // Assign the CSRF data to the view
        $this->view['csrf'] = [
            'name_key' => $nameKey,
            'name' => $request->getAttribute($nameKey),
            'value_key' => $valueKey,
            'value' => $request->getAttribute($valueKey)
        ];
    }

    // Allow constructor methods to easily access properties from the container
    public function &__get(string $name)
    {
        return $this->container->$name;
    }
}
