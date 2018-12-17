<?php
namespace Api\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MpMenus Model
 *
 * @property \Api\Model\Table\MpMenusTable|\Cake\ORM\Association\BelongsTo $ParentMpMenus
 * @property \Api\Model\Table\MpsTable|\Cake\ORM\Association\BelongsTo $Mps
 * @property \Api\Model\Table\MpMenusTable|\Cake\ORM\Association\HasMany $ChildMpMenus
 *
 * @method \Api\Model\Entity\MpMenu get($primaryKey, $options = [])
 * @method \Api\Model\Entity\MpMenu newEntity($data = null, array $options = [])
 * @method \Api\Model\Entity\MpMenu[] newEntities(array $data, array $options = [])
 * @method \Api\Model\Entity\MpMenu|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpMenu|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Api\Model\Entity\MpMenu patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Api\Model\Entity\MpMenu[] patchEntities($entities, array $data, array $options = [])
 * @method \Api\Model\Entity\MpMenu findOrCreate($search, callable $callback = null, $options = [])
 */
class MpMenusTable extends Table
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

        $this->setTable('mp_menus');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('ParentMpMenus', [
            'className' => 'Api.MpMenus',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsTo('Mps', [
            'foreignKey' => 'mp_id',
            'joinType' => 'INNER',
            'className' => 'Api.Mps'
        ]);
        $this->hasMany('ChildMpMenus', [
            'className' => 'Api.MpMenus',
            'foreignKey' => 'parent_id',
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
            ->scalar('name')
            ->maxLength('name', 50, '菜单名称不能超过50字符')
            ->requirePresence('name', 'create', '菜单名称不能未空')
            ->notEmpty('name');

        $validator
            ->scalar('type')
            ->maxLength('type', 50, '类型过长')
            ->allowEmpty('type');

        $validator
            ->scalar('content')
            ->maxLength('content', 255, '内容过长')
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
        $rules->add($rules->existsIn(['parent_id'], 'ParentMpMenus', '无匹配父菜单'));
        $rules->add($rules->existsIn(['mp_id'], 'Mps', '无匹配公众号'));

        return $rules;
    }


    /**
     * json 化菜单content
     * @param array $button
     * @return false|string
     */
    public function encodeContent($button = [])
    {
        unset($button['type']);
        unset($button['name']);
        unset($button['sub_button']);
        return json_encode($button);
    }


    /**
     * 解析 菜单content
     * @param null $content
     * @return array|mixed
     */
    public function decodeContent($content = null)
    {
        return empty($content) ? null : json_decode($content, true);
    }
}
