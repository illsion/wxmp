<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MpRules Model
 *
 * @property \Api\Model\Table\MpsTable|\Cake\ORM\Association\BelongsTo $Mps
 * @property \Api\Model\Table\MpMessagesTable|\Cake\ORM\Association\BelongsTo $MpMessages
 *
 * @method \Api\Model\Entity\MpRule get($primaryKey, $options = [])
 * @method \Api\Model\Entity\MpRule newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\MpRule[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\MpRule|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpRule|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpRule patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\MpRule[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\MpRule findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MpRulesTable extends Table
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

        $this->setTable('mp_rules');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Mps', [
            'foreignKey' => 'mp_id',
            'joinType' => 'INNER',
            'className' => 'Api.Mps'
        ]);

        $this->belongsTo('MpMessages', [
            'foreignKey' => 'mp_message_id',
            'joinType' => 'INNER',
            'className' => 'Api.MpMessages',
            'dependent' => true
        ]);

        $this->hasOne('MpEvents', [
            'foreignKey' => 'mp_rule_id',
            'className' => 'Api.MpEvents',
            'dependent' => true
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
            ->scalar('keywords')
            ->maxLength('keywords', 50, '关键字不能超过50个字符')
            ->requirePresence('keywords', 'create', '关键字不能为空')
            ->notEmpty('keywords', '关键字不能为空')
            ->add('keywords', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => '关键字已存在']);

        $validator
            ->scalar('type')
            ->maxLength('type', 24)
            ->requirePresence('type', 'create', '类型不能为空')
            ->notEmpty('type', '类型不能为空');


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
        $rules->add($rules->isUnique(['keywords'], '关键字已存在'));
        $rules->add($rules->existsIn(['mp_id'], 'Mps', '无匹配公众号'));

        return $rules;
    }


    public function getStatus()
    {
        return [
            1 => '开启',
            2 => '关闭'
        ];
    }

    /**
     * 类型 当前只支持 文字与图片消息
     * @return array
     */
    public function getType()
    {
        return [
            'text' => '文字',
            'image' => '图片'
        ];
    }

    /**
     * 类型 需要输入的字段
     * @return array
     */
    public function getTypeFields()
    {
        return [
            'text' => [
                'content' => true
            ],
            'image' => [
                'description' => true,
                'url' => true,
                'media_url' => true
            ]
        ];
    }
}
