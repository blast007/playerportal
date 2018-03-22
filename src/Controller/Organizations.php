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
use Slim\Http\Request;
use Slim\Http\Response;

class Organizations extends Controller
{
    public function index(Request $request, Response $response, array $args)
    {
        $organizations = $this->db
            ->getModel(OrganizationsModel::class)
            ->findByOrganizationMember($_SESSION['user']['user_id'])
            ->extract()
        ;

        return $this->view->render($response, 'Organizations/index.twig', compact('organizations'));
    }
}
