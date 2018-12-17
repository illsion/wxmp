<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Mps Model
 *
 * @property \Api\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \Api\Model\Entity\Mp get($primaryKey, $options = [])
 * @method \Api\Model\Entity\Mp newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\Mp[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\Mp|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\Mp|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\Mp patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\Mp[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\Mp findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MpsTable extends Table
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

        $this->setTable('mps');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => 'Api.Users'
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
            ->scalar('name')
            ->maxLength('name', 32, '公众号名称最多32字符')
            ->requirePresence('name', 'create', '公众号名称不能为空')
            ->notEmpty('name', '公众号名称不能为空');

        $validator
            ->requirePresence('user_id', true, '关联用户不能为空')
            ->notEmpty('user_id', '关联用户不能为空');

        $validator
            ->scalar('appid')
            ->maxLength('appid', 50, 'appid最多50字符')
            ->requirePresence('appid', 'create', 'appid不能为空')
            ->notEmpty('appid', 'appid不能为空');

        $validator
            ->scalar('origin_id')
            ->maxLength('origin_id', 50, '公众号原始id最多50字符')
            ->requirePresence('origin_id', 'create', '公众号原始id不能为空')
            ->notEmpty('origin_id', '公众号原始id不能为空');

        $validator
            ->scalar('secret')
            ->maxLength('secret', 50, 'appsecret最多50字符')
            ->requirePresence('secret', 'create', 'appsecret不能为空')
            ->notEmpty('secret', 'appsecret不能为空');

        $validator
            ->scalar('token')
            ->maxLength('token', 50, 'token最多50字符')
            ->requirePresence('token', 'create', 'token不能为空')
            ->notEmpty('token', 'token不能为空');

        $validator
            ->requirePresence('type', 'create', '公众号类型不能为空')
            ->notEmpty('type', '公众号类型不能为空');

        $validator
            ->scalar('description')
            ->allowEmpty('description');

        $validator
            ->scalar('qrcode')
            ->maxLength('qrcode', 80, '二维码路径过长')
            ->allowEmpty('qrcode');

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
        $rules->add($rules->existsIn(['user_id'], 'Users', '关联用户不存在'));

        return $rules;
    }

    /**
     * 获取公众号类型
     * @return array
     */
    public function getType()
    {
        return [
            1 => '订阅号',
            2 => '服务号'
        ];
    }

    /**
     * 验证公众号
     * @param $id
     * @param $user_id
     * @param bool $bool 是否返回bool值
     * @return array|bool|\Cake\Datasource\EntityInterface|null
     */
    public function validateMp($id, $user_id, $bool = true)
    {
        $data = $this->find()
            ->where([
                'id' => $id,
                'user_id' => $user_id
            ])
            ->first();

        return $bool ? !empty($data) : $data;

    }


}
