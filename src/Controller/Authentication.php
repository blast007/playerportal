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

use App\Model\PlayerPortal\PublicSchema\UsersModel;
use Slim\Http\Request;
use Slim\Http\Response;

class Authentication extends Controller
{
    public function login(Request $request, Response $response, array $args)
    {
        // Check the login
        if ($request->isPost()) {
            $username = $request->getParsedBodyParam('username');
            $password = $request->getParsedBodyParam('password');

            if (!$username || !$password) {
                $this->view['error'] = 'Invalid username or password.';
            } else {
                // Search for a user
                $user = $this->db
                    ->getModel(UsersModel::class)
                    ->findWhere('username ~* $*', compact('username'))
                ;

                if ($user->count() !== 1 || $this->password->verifyHash($password, $user->get(0)['password']) !== true) {
                    $this->view['error'] = 'Invalid username or password.';
                } else {
                    $u = $user->get(0);
                    $_SESSION['user'] = [
                        'username' => $u['username'],
                        'user_id' => $u['id'],
                        'ip_address' => $_SERVER['REMOTE_ADDR']
                    ];

                    return $response->withRedirect('/');
                }
            }
        }

        $this->assignCSRF($request);
        return $this->view->render($response, 'login.twig');
    }

    public function logout(Request $request, Response $response, array $args)
    {
        unset($_SESSION['user']);
        return $response->withRedirect('/');
    }
}
