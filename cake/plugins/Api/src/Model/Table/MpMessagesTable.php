<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MpMessages Model
 *
 * @property \Api\Model\Table\MpRulesTable|\Cake\ORM\Association\HasMany $MpRules
 *
 * @method \Api\Model\Entity\MpMessage get($primaryKey, $options = [])
 * @method \Api\Model\Entity\MpMessage newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\MpMessage[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\MpMessage|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpMessage|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpMessage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\MpMessage[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\MpMessage findOrCreate($search, callable $callback = null, $options = [])
 */
class MpMessagesTable extends Table
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

        $this->setTable('mp_messages');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->hasOne('MpRules', [
            'foreignKey' => 'mp_message_id',
            'className' => 'Api.MpRules'
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
            ->maxLength('title', 50, '标题不能超过50字符')
            ->requirePresence('title', 'create', '标题不能为空')
            ->notEmpty('title', '标题不能为空');

        $validator
            ->scalar('description')
            ->maxLength('description', 255, '描述不能超过255字符')
            ->allowEmpty('description');

        $validator
            ->scalar('content')
            ->allowEmpty('content');

        $validator
            ->scalar('url')
            ->maxLength('url', 120, '链接过长')
            ->allowEmpty('url');

        $validator
            ->scalar('media_url')
            ->maxLength('media_url', 120, '资源链接过长')
            ->allowEmpty('media_url');

        return $validator;
    }
}
