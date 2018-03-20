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
    public function __construct() {
        $this->structure = new OrganizationsStructure;
        $this->flexible_entity_class = '\App\Model\PlayerPortal\PublicSchema\Organizations';
    }

    public function findByOrganizationMember($user_id) {
        $users_organizations_model = $this
            ->getSession()
            ->getModel('\App\Model\PlayerPortal\PublicSchema\UsersOrganizationsModel');

        $sql = <<<SQL
SELECT 
    {projection}
FROM
    {organizations} org
    INNER JOIN {users_organizations} uo on org.id = uo.organization
WHERE
    uo.user = $*
SQL;

        $projection = $this->createProjection()
            ->setField("owner", "uo.owner", "bool")
            ->setField("hosting_admin", "uo.hosting_admin", "bool")
            ->setField("group_admin", "uo.group_admin", "bool")
            ->setField("group_manager", "uo.group_manager", "bool")
            ;

        $sql = strtr(
            $sql,
            [
                '{organizations}' => $this->structure->getRelation(),
                '{users_organizations}' => $users_organizations_model->getStructure()->getRelation(),
                '{projection}' => $projection->formatFields('org'),
            ]
        );

        return $this->query($sql, [$user_id], $projection);
    }
}
