<?php
/**
 * This file has been automatically generated by Pomm's generator.
 * You MIGHT NOT edit this file as your changes will be lost at next
 * generation.
 */

namespace App\Model\PlayerPortal\PublicSchema\AutoStructure;

use PommProject\ModelManager\Model\RowStructure;

/**
 * UsersGroups
 *
 * Structure class for relation public.users_groups.
 * 
 * Class and fields comments are inspected from table and fields comments.
 * Just add comments in your database and they will appear here.
 * @see http://www.postgresql.org/docs/9.0/static/sql-comment.html
 *
 *
 *
 * @see RowStructure
 */
class UsersGroups extends RowStructure
{
    /**
     * __construct
     *
     * Structure definition.
     *
     * @access public
     */
    public function __construct()
    {
        $this
            ->setRelation('public.users_groups')
            ->setPrimaryKey(['group', 'user'])
            ->addField('user', 'int4')
            ->addField('group', 'int4')
            ->addField('manager', 'bool')
            ->addField('implicit', 'bool')
            ;
    }
}