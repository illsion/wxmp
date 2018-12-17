<?php
namespace Api\Model\Entity;

use Cake\ORM\Entity;

/**
 * MpMenu Entity
 *
 * @property int $id
 * @property int $parent_id
 * @property int $mp_id
 * @property string $name
 * @property string|null $type
 * @property string|null $content
 * @property int $sort
 *
 * @property \Api\Model\Entity\ParentMpMenu $parent_mp_menu
 * @property \Api\Model\Entity\Mp $mp
 * @property \Api\Model\Entity\ChildMpMenu[] $child_mp_menus
 */
class MpMenu extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'parent_id' => true,
        'mp_id' => true,
        'name' => true,
        'type' => true,
        'content' => true,
        'sort' => true,
        'parent_mp_menu' => true,
        'mp' => true,
        'child_mp_menus' => true
    ];
}
