<?php

namespace App\Model\PlayerPortal\PublicSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use App\Model\PlayerPortal\PublicSchema\AutoStructure\Organizations as OrganizationsStructure;
use App\Model\PlayerPortal\PublicSchema\Organizations;

/**
 * OrganizationsModel
 *
 * Model class for table organizations.
 *
 * @see Model
 */
class OrganizationsModel extends Model
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
        $this->structure = new OrganizationsStructure;
        $this->flexible_entity_class = '\App\Model\PlayerPortal\PublicSchema\Organizations';
    }
}
