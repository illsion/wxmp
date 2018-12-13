<?php
namespace Api\Model\Entity;

use Cake\ORM\Entity;

/**
 * MpEvent Entity
 *
 * @property int $id
 * @property int $mp_id
 * @property string $mp_rule_keywords
 * @property string $name
 * @property int $status
 *
 * @property \Api\Model\Entity\Mp $mp
 */
class MpEvent extends Entity
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
        'mp_id' => true,
        'mp_rule_id' => true,
        'name' => true,
        'status' => true,
        'mp' => true,
        'mp_rule' => true
    ];
}
