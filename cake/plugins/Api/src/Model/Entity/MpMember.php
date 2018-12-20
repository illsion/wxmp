<?php
namespace Api\Model\Entity;

use Cake\ORM\Entity;

/**
 * MpMember Entity
 *
 * @property int $id
 * @property string $openid
 * @property int $mp_id
 * @property string $nickname
 * @property int $sex
 * @property string|null $city
 * @property string|null $province
 * @property string|null $country
 * @property string|null $headimgurl
 * @property int|null $subscribe_time
 * @property int|null $unsubscribe_time
 * @property int $subscribe
 *
 * @property \Api\Model\Entity\Mp $mp
 */
class MpMember extends Entity
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
        'openid' => true,
        'mp_id' => true,
        'nickname' => true,
        'sex' => true,
        'city' => true,
        'province' => true,
        'country' => true,
        'headimgurl' => true,
        'subscribe_time' => true,
        'unsubscribe_time' => true,
        'subscribe' => true,
        'mp' => true
    ];
}
