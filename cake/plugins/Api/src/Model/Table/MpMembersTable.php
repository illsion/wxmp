<?php
namespace Api\Model\Table;

use Cake\Collection\Collection;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * MpMembers Model
 *
 * @property \Api\Model\Table\MpsTable|\Cake\ORM\Association\BelongsTo $Mps
 *
 * @method \Api\Model\Entity\MpMember get($primaryKey, $options = [])
 * @method \Api\Model\Entity\MpMember newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\MpMember[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\MpMember|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpMember|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpMember patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\MpMember[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\MpMember findOrCreate($search, callable $callback = null, $options = [])
 */
class MpMembersTable extends Table
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

        $this->setTable('mp_members');
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
            ->scalar('nickname')
            ->maxLength('nickname', 64)
            ->requirePresence('nickname', 'create')
            ->notEmpty('nickname');


        $validator
            ->scalar('city')
            ->maxLength('city', 24)
            ->allowEmpty('city');

        $validator
            ->scalar('province')
            ->maxLength('province', 24)
            ->allowEmpty('province');

        $validator
            ->scalar('country')
            ->maxLength('country', 24)
            ->allowEmpty('country');

        $validator
            ->scalar('headimgurl')
            ->maxLength('headimgurl', 255)
            ->allowEmpty('headimgurl');

        $validator
            ->integer('subscribe_time')
            ->allowEmpty('subscribe_time');

        $validator
            ->integer('unsubscribe_time')
            ->allowEmpty('unsubscribe_time');


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


    /**
     * 单用户关注添加函数
     * @param array $data 用户信息
     * @param null $mpId 公众号id
     * @return \Api\Model\Entity\MpMember|bool
     */
    public function subscribe($data = [], $mpId = null)
    {
        if (!empty($data) && !empty($mpId)) {

            $entity = $this->find()
                ->where([
                    'openid' => $data['openid'],
                    'mp_id' => $mpId
                ])
                ->first();

            if (empty($entity)) {
                $entity = $this->newEntity();
                $data['mp_id'] = $mpId;
            } else {
                $data['id'] = $entity['id'];
            }


            $data = $this->patchEntity($entity, $data);


            return $this->save($data);

        }

        return false;

    }


    /**
     * 单用户取消关注
     * @param null $openid
     * @param null $mpId
     * @return \Api\Model\Entity\MpMember|bool
     */
    public function unsubscribe($openid = null, $mpId = null)
    {
        if (!empty($openid) && !empty($mpId)) {
            $entity = $this->find()
                ->select([
                    'id', 'unsubscribe_time', 'subscribe'
                ])
                ->where([
                    'openid' => $openid,
                    'mp_id' => $mpId
                ])
                ->first();

            if (!empty($entity)) {
                $data = $this->patchEntity($entity, [
                    'id' => $entity['id'],
                    'subscribe' => 0,
                    'unsubscribe_time' => time()
                ]);

                return $this->save($data);
            }


        }

        return false;

    }

    /**
     * 同步用户
     * @param array $data
     * @param null $mpId
     * @return bool|\Cake\Datasource\EntityInterface[]|\Cake\ORM\ResultSet
     * @throws \Exception
     */
    public function synchronizeData($data = [], $mpId = null)
    {

        if (!empty($mpId) && !empty($data)) {
            $this->deleteAll([
                'mp_id' => $mpId
            ]);

            $collection = new Collection($data);

            $data = $collection->filter(function ($value) {
                return $value['subscribe'] == 1;
            })->map(function ($value) use ($mpId) {
                $value['mp_id'] = $mpId;
                return $value;
            })->toList();

            $entity = $this->newEntities($data);

            return $this->saveMany($entity);

        }

        return false;

    }


}
