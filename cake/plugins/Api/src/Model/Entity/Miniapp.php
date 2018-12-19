<?php
namespace Api\Model\Entity;

use Cake\ORM\Entity;

/**
 * Miniapp Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $appid
 * @property string $secret
 * @property string $token
 * @property \Cake\I18n\FrozenTime|null $created
 *
 * @property \Api\Model\Entity\User $user
 */
class Miniapp extends Entity
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
        'user_id' => true,
        'name' => true,
        'appid' => true,
        'secret' => true,
        'token' => true,
        'created' => true,
        'user' => true
    ];

}
