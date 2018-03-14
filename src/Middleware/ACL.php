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

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class ACL {
    private $require_authentication;

    public function __construct($settings) {
        $this->require_authentication = $settings['require_authentication'] ?? false;
    }

    public function __invoke(Request $request, Response $response, callable $next) {
        $allow = true;
        $message = '';

        // Does this route require authentication?
        if ($this->require_authentication && !isset($_SESSION['user']['user_id'])) {
            $allow = false;
            $message = 'You must be logged in to access this page.';
        }


        // If the user is allowed to access this route, pass it through
        if ($allow === true) {
            return $next($request, $response);
        } else {
            // Otherwise, set the flash message and redirect to the login page
            $_SESSION['flash_message'] = $message;
            return $response->withRedirect('/login?return_to='.urlencode($request->getUri()));
        }
    }
}