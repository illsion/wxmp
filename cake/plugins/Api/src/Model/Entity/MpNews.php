<?php
namespace Api\Model\Entity;

use Cake\ORM\Entity;

/**
 * MpNews Entity
 *
 * @property int $id
 * @property int $mp_id
 * @property string $title
 * @property int $type
 * @property int $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \Api\Model\Entity\Mp $mp
 * @property \Api\Model\Entity\MpNewsList[] $mp_news_lists
 */
class MpNews extends Entity
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
        'title' => true,
        'type' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'mp' => true,
        'mp_news_lists' => true
    ];
}
