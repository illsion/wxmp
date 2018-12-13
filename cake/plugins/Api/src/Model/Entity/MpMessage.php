<?php
namespace Api\Model\Entity;

use Cake\ORM\Entity;

/**
 * MpMessage Entity
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $content
 * @property string|null $url
 * @property string|null $media_url
 *
 * @property \Api\Model\Entity\MpRule[] $mp_rules
 */
class MpMessage extends Entity
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
        'title' => true,
        'description' => true,
        'content' => true,
        'url' => true,
        'media_url' => true,
        'mp_rules' => true
    ];
}
