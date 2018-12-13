<?php
namespace Api\Model\Entity;

use Cake\ORM\Entity;

/**
 * MpRule Entity
 *
 * @property int $id
 * @property int $mp_id
 * @property int $mp_message_id
 * @property string $keywords
 * @property string $type
 * @property int $status
 * @property \Cake\I18n\FrozenTime|null $created
 *
 * @property \Api\Model\Entity\Mp $mp
 * @property \Api\Model\Entity\MpMessage $mp_message
 */
class MpRule extends Entity
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
        'mp_message_id' => true,
        'keywords' => true,
        'type' => true,
        'status' => true,
        'created' => true,
        'mp' => true,
        'mp_message' => true
    ];
}
