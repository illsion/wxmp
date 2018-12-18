<?php
namespace Api\Model\Entity;

use Cake\ORM\Entity;

/**
 * MpMemberOpenid Entity
 *
 * @property int $id
 * @property int $mp_id
 * @property string $openid
 *
 * @property \Api\Model\Entity\Mp $mp
 */
class MpMemberOpenid extends Entity
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
        'openid' => true,
        'mp' => true
    ];
}
