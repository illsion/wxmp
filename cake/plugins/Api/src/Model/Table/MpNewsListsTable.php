<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MpNewsLists Model
 *
 * @property \Api\Model\Table\MpNewsTable|\Cake\ORM\Association\BelongsTo $MpNews
 * @property \Api\Model\Table\ThumbMediaTable|\Cake\ORM\Association\BelongsTo $ThumbMedia
 *
 * @method \Api\Model\Entity\MpNewsList get($primaryKey, $options = [])
 * @method \Api\Model\Entity\MpNewsList newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\MpNewsList[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\MpNewsList|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpNewsList|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpNewsList patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\MpNewsList[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\MpNewsList findOrCreate($search, callable $callback = null, $options = [])
 */
class MpNewsListsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('mp_news_lists');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('MpNews', [
            'foreignKey' => 'mp_news_id',
            'joinType' => 'INNER',
            'className' => 'Api.MpNews'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->nonNegativeInteger('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('title')
            ->allowEmpty('title');

        $validator
            ->scalar('author')
            ->maxLength('author', 120, '作者超出最大长度限制')
            ->allowEmpty('author');

        $validator
            ->scalar('content_source_url')
            ->maxLength('content_source_url', 255, '原文链接超出最大长度限制')
            ->allowEmpty('content_source_url');

        $validator
            ->scalar('digest')
            ->allowEmpty('digest');

        $validator
            ->scalar('content')
            ->allowEmpty('content');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['mp_news_id'], 'MpNews', '无匹配群发消息'));

        return $rules;
    }
}
