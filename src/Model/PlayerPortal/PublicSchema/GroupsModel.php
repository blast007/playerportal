<?php

namespace App\Model\PlayerPortal\PublicSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use App\Model\PlayerPortal\PublicSchema\AutoStructure\Groups as GroupsStructure;
use App\Model\PlayerPortal\PublicSchema\Groups;

/**
 * GroupsModel
 *
 * Model class for table groups.
 *
 * @see Model
 */
class GroupsModel extends Model
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
        $this->structure = new GroupsStructure;
        $this->flexible_entity_class = '\App\Model\PlayerPortal\PublicSchema\Groups';
    }
}
