<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @method \Api\Model\Entity\User get($primaryKey, $options = [])
 * @method \Api\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\User|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->scalar('username')
            ->maxLength('username', 32, '用户名最大不能超过32字符')
            ->requirePresence('username', 'create', '用户名不能为空')
            ->notEmpty('username', '用户名不能为空')
            ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => '该用户名已存在！']);

        $validator
            ->scalar('password')
            ->maxLength('password', 64)
            ->requirePresence('password', 'create', '密码不能为空')
            ->notEmpty('password', '密码不能为空');


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
        $rules->add($rules->isUnique(['username'], '该用户名已存在！'));

        return $rules;
    }

    /**
     * 账号状态
     * @return array
     */
    public function getStatus()
    {
        return [
            1 => '正常',
            2 => '冻结'
        ];
    }
}
