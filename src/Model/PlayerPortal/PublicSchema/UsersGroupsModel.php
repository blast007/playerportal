<?php

namespace App\Model\PlayerPortal\PublicSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use App\Model\PlayerPortal\PublicSchema\AutoStructure\UsersGroups as UsersGroupsStructure;
use App\Model\PlayerPortal\PublicSchema\UsersGroups;

/**
 * UsersGroupsModel
 *
 * Model class for table users_groups.
 *
 * @see Model
 */
class UsersGroupsModel extends Model
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
        $this->structure = new UsersGroupsStructure;
        $this->flexible_entity_class = '\App\Model\PlayerPortal\PublicSchema\UsersGroups';
    }
}
