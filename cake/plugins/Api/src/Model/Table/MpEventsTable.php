<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * MpEvents Model
 *
 * @property \Api\Model\Table\MpsTable|\Cake\ORM\Association\BelongsTo $Mps
 * @property \Api\Model\Table\MpRulesTable|\Cake\ORM\Association\BelongsTo $MpRules
 *
 * @method \Api\Model\Entity\MpEvent get($primaryKey, $options = [])
 * @method \Api\Model\Entity\MpEvent newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\MpEvent[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\MpEvent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpEvent|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpEvent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\MpEvent[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\MpEvent findOrCreate($search, callable $callback = null, $options = [])
 */
class MpEventsTable extends Table
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

        $this->setTable('mp_events');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Mps', [
            'foreignKey' => 'mp_id',
            'joinType' => 'INNER',
            'className' => 'Api.Mps'
        ]);

        $this->belongsTo('MpRules', [
            'foreignKey' => 'mp_rule_id',
            'joinType' => 'INNER',
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
            ->scalar('mp_id')
            ->requirePresence('mp_id', 'create', '请选择公众号')
            ->notEmpty('mp_id', '无匹配公众号');

        $validator
            ->scalar('mp_rule_id')
            ->requirePresence('mp_rule_id', 'create', '无匹配规则')
            ->notEmpty('mp_rule_id', '无匹配规则');

        $validator
            ->scalar('name')
            ->maxLength('name', 50, '事件类型过长')
            ->requirePresence('name', 'create', '事件类型不能为空')
            ->notEmpty('name', '事件类型不能为空');


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
        $rules->add($rules->existsIn(['mp_id'], 'Mps', '无匹配公众号'));

        return $rules;
    }


    /**
     * 获取事件类型
     * @return array
     */
    public function getName()
    {
        return [
            'subscribe' => '关注',
            'unsubscribe' => '取消关注',
            'image' => '收到图片消息',
            'voice' => '收到语音消息',
            'video' => '收到视频消息',
            'location' => '收到坐标消息',
            'link' => '收到链接消息'
        ];

    }

    public function getStatus()
    {
        return [
            1 => '开启',
            2 => '关闭'
        ];
    }


    /**
     * 获取关联规则id
     * @param null $mp_id
     * @param null $keywords
     * @return mixed|null
     */
    public function getRuleId($mp_id = null, $keywords = null)
    {
        $data = TableRegistry::getTableLocator()->get('Api.MpRules')->find()
            ->where([
                'mp_id' => $mp_id,
                'keywords' => $keywords
            ])
            ->select([
                'id'
            ])
            ->first();

        return !empty($data) ? $data['id'] : null;

    }
}
