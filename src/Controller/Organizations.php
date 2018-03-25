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

use App\Model\PlayerPortal\PublicSchema\OrganizationsModel;
use App\Model\PlayerPortal\PublicSchema\UsersModel;
use App\Model\PlayerPortal\PublicSchema\GroupsModel;
use Slim\Http\Request;
use Slim\Http\Response;
use Particle\Validator\Validator;
use Particle\Validator\Exception\InvalidValueException;

class Organizations extends Controller
{
    public function index(Request $request, Response $response, array $args)
    {
        // Retrieve a list of all organizations that the logged in user is associated with
        $organizations = $this->db
            ->getModel(OrganizationsModel::class)
            ->findByOrganizationMember($_SESSION['user']['user_id'])
            ->extract()
        ;

        return $this->view->render($response, 'Organizations/index.twig', compact('organizations'));
    }

    public function create(Request $request, Response $response, array $args)
    {
        if ($request->isPost()) {
            $validator = new Validator;

            // Establish rules
            $validator->required('short_name', 'Short name')
                ->lengthBetween(2, 32)
                ->alnum()
                ->callback(function ($short_name) {
                    // Check if the organization already exists
                    $organization = $this->db
                        ->getModel(OrganizationsModel::class)
                        ->findWhere('short_name ~* $*', compact('short_name'))
                    ;

                    // If an organization with this short name exists, throw an exception
                    if ($organization->count() > 0) {
                        throw new InvalidValueException('An organization already exists with this short name. Please pick a different name.', 'short_name');
                    }

                    // Otherwise we are good to go!
                    return true;
                })
            ;
            $validator->required('display_name', 'Display name')
                ->lengthBetween(2, 64)
                ->alnum(true)
            ;

            // Grab form data
            $data = $request->getParsedBody();

            // Validate form data against rules
            $result = $validator->validate($data);

            // Did all the rules pass validation?
            if ($result->isValid()) {
                // Try to add it to the DB
                $organization = $this->db
                    ->getModel(OrganizationsModel::class)
                    ->createAndSave([
                        'founder' => $_SESSION['user']['user_id'],
                        'short_name' => $data['short_name'],
                        'display_name' => $data['display_name']
                    ])
                ;

                // If the organization was created, redirect
                if ($organization) {
                    return $response->withRedirect(
                        $this->router->pathFor('organizations_edit', [
                            'short_name' => $data['short_name']
                        ])
                    );
                }
            } else {
                // Assign the error messages to the view
                $this->view['errors'] = $result->getMessages();
            }
        }

        $this->assignCSRF($request);
        return $this->view->render($response, 'Organizations/create.twig');
    }

    public function edit(Request $request, Response $response, array $args)
    {
        // Try to fetch the organization
        $organization = $this->db
            ->getModel(OrganizationsModel::class)
            ->findWhere('short_name ~* $*', ['short_name' => $args['short_name']])
            ->extract()
        ;

        if (sizeof($organization) < 1) {
            return $response->withRedirect($this->router->pathFor('organizations'));
        }

        $organization = $organization[0];

        // Fetch organization users
        $organization_users = $this->db
            ->getModel(UsersModel::class)
            ->findByOrganizationMember($organization['id]'])
            ->extract()
        ;

        // Fetch organization groups
        $groups = $this->db
            ->getModel(GroupsModel::class)
            ->findWhere('organization = $*', ['organization' => $organization['id']])
            ->extract()
        ;
        ;


        return $this->view->render(
            $response,
            'Organizations/edit.twig',
            compact('organization', 'organization_users', 'groups')
        );
    }
}
