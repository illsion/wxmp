<?php
namespace Api\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mp Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $appid
 * @property string $secret
 * @property string|null $token
 * @property string $origin_id
 * @property int $type
 * @property string|null $description
 * @property \Cake\I18n\FrozenTime|null $created
 * @property string|null $qrcode
 *
 * @property \Api\Model\Entity\User $user
 */
class Mp extends Entity
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
        'origin_id' => true,
        'type' => true,
        'description' => true,
        'created' => true,
        'qrcode' => true,
        'user' => true
    ];

}
