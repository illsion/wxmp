<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MpMemberOpenid Model
 *
 * @property \Api\Model\Table\MpsTable|\Cake\ORM\Association\BelongsTo $Mps
 *
 * @method \Api\Model\Entity\MpMemberOpenid get($primaryKey, $options = [])
 * @method \Api\Model\Entity\MpMemberOpenid newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\MpMemberOpenid[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\MpMemberOpenid|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpMemberOpenid|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpMemberOpenid patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\MpMemberOpenid[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\MpMemberOpenid findOrCreate($search, callable $callback = null, $options = [])
 */
class MpMemberOpenidTable extends Table
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

        $this->setTable('mp_member_openid');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Mps', [
            'foreignKey' => 'mp_id',
            'joinType' => 'INNER',
            'className' => 'Api.Mps'
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
            ->scalar('openid')
            ->maxLength('openid', 64)
            ->requirePresence('openid', 'create')
            ->notEmpty('openid');


        $validator
            ->scalar('mp_id')
            ->requirePresence('mp_id', 'create', '请选择公众号')
            ->notEmpty('mp_id', '无匹配公众号');

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
        $rules->add($rules->existsIn(['mp_id'], 'Mps'));

        return $rules;
    }
}
