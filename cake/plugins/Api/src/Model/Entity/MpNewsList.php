<?php
namespace Api\Model\Entity;

use Cake\ORM\Entity;

/**
 * MpNewsList Entity
 *
 * @property int $id
 * @property int $mp_news_id
 * @property string $title
 * @property string|null $author
 * @property string|null $content_source_url
 * @property string|null $digest
 * @property string|null $content
 * @property string|null $thumb_media_id
 *
 * @property \Api\Model\Entity\MpNews $mp_news
 * @property \Api\Model\Entity\ThumbMedia $thumb_media
 */
class MpNewsList extends Entity
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
        'mp_news_id' => true,
        'title' => true,
        'author' => true,
        'content_source_url' => true,
        'digest' => true,
        'content' => true,
        'thumb_media_id' => true,
        'thumb_media_path' => true,
        'mp_news' => true
    ];
}
