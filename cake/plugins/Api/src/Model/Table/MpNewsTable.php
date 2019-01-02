<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MpNews Model
 *
 * @property \Api\Model\Table\MpsTable|\Cake\ORM\Association\BelongsTo $Mps
 * @property \Api\Model\Table\MpNewsListsTable|\Cake\ORM\Association\HasMany $MpNewsLists
 *
 * @method \Api\Model\Entity\MpNews get($primaryKey, $options = [])
 * @method \Api\Model\Entity\MpNews newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\MpNews[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\MpNews|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpNews|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpNews patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\MpNews[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\MpNews findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MpNewsTable extends Table
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

        $this->setTable('mp_news');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Mps', [
            'foreignKey' => 'mp_id',
            'joinType' => 'INNER',
            'className' => 'Api.Mps'
        ]);

        $this->hasMany('MpNewsLists', [
            'foreignKey' => 'mp_news_id',
            'className' => 'Api.MpNewsLists',
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
            ->scalar('mp_id')
            ->requirePresence('mp_id', 'create', '请选择公众号')
            ->notEmpty('mp_id', '无匹配公众号');

        $validator
            ->scalar('title')
            ->allowEmpty('title');


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

}
