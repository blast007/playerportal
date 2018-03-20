<?php

namespace App\Model\PlayerPortal\PublicSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use App\Model\PlayerPortal\PublicSchema\AutoStructure\UsersOrganizations as UsersOrganizationsStructure;
use App\Model\PlayerPortal\PublicSchema\UsersOrganizations;

/**
 * UsersOrganizationsModel
 *
 * Model class for table users_organizations.
 *
 * @see Model
 */
class UsersOrganizationsModel extends Model
{
    use WriteQueries;

    /**
     * __construct()
     *
     * Model constructor
     *
     * @access public
     */
    public function __construct()
    {
        $this->structure = new UsersOrganizationsStructure;
        $this->flexible_entity_class = '\App\Model\PlayerPortal\PublicSchema\UsersOrganizations';
    }
}
