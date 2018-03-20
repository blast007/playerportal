<?php

namespace App\Model\PlayerPortal\PublicSchema;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use App\Model\PlayerPortal\PublicSchema\AutoStructure\ServerKeys as ServerKeysStructure;
use App\Model\PlayerPortal\PublicSchema\ServerKeys;

/**
 * ServerKeysModel
 *
 * Model class for table server_keys.
 *
 * @see Model
 */
class ServerKeysModel extends Model
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
        $this->structure = new ServerKeysStructure;
        $this->flexible_entity_class = '\App\Model\PlayerPortal\PublicSchema\ServerKeys';
    }
}
