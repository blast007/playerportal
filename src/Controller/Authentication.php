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
use Particle\Validator\Validator;
use Particle\Validator\Exception\InvalidValueException;

class Authentication extends Controller
{
    public function login(Request $request, Response $response, array $args)
    {
        // Check the login
        if ($request->isPost()) {
            $validator = new Validator;
            $user = null;

            // Establish rules
            $validator->required('username', 'Username')
                ->lengthBetween(2, 32)
                ->regex("^[a-zA-Z0-9_\[\]][a-zA-Z0-9 \-+_\[\]]*[a-zA-Z0-9\-+_\[\]]$")
                ->callback(function ($value, array $values) use (&$user) {
                    // Query to get users matching this username (which should return 0 or 1 columns)
                    $user = $this->db
                        ->getModel(UsersModel::class)
                        ->findWhere('username ~* $*', ['username' => $value])
                    ;

                    // If we didn't get one user or the password hash doesn't match, throw an exception
                    if ($user->count() !== 1 || $this->password->verifyHash($values['password'], $user->get(0)['password']) !== true) {
                        throw new InvalidValueException('Invalid username or password.', 'username');
                    }

                    // TODO: Add additional checks?  Bans?

                    // Otherwise, we're good to go!
                    return true;
                })
            ;
            $validator->required('password', 'Password')
                ->lengthBetween(4, null)
            ;

            // Grab form data
            $data = $request->getParsedBody();

            // Validate form data against rules
            $result = $validator->validate($data);

            // Did all the rules pass validation?
            if ($result->isValid()) {
                // Get the user data
                $u = $user->get(0);

                // Set the session variables
                $_SESSION['user'] = [
                    'username' => $u['username'],
                    'user_id' => $u['id'],
                    'ip_address' => $_SERVER['REMOTE_ADDR']
                ];

                // Redirect
                return $response->withRedirect('/');
            } else {
                // Assign the errors to the view
                $this->view['errors'] = $result->getMessages();
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
