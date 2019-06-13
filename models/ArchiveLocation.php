<?php
/**
 * ArchiveLocation
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 April 2019, 08:37 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 * This is the model class for table "ommu_archive_location".
 *
 * The followings are the available columns in table "ommu_archive_location":
 * @property integer $id
 * @property integer $publish
 * @property integer $parent_id
 * @property string $type
 * @property string $location_name
 * @property string $location_desc
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArchiveRoomStorage[] $roomStorage
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\archive\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use ommu\users\models\Users;
use yii\helpers\ArrayHelper;
use yii\base\Event;

class ArchiveLocation extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['type', 'location_desc', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $parentName;
	public $creationDisplayname;
	public $modifiedDisplayname;

	public $building;
	public $storage;

	const TYPE_BUILDING = 'building';
	const TYPE_DEPO = 'depo';
	const TYPE_ROOM = 'room';

	const SCENARIO_NOT_BUILDING = 'notBuildingForm';
	const SCENARIO_ROOM = 'roomForm';
	const EVENT_BEFORE_SAVE_ARCHIVE_LOCATION = 'BeforeSaveArchiveLocation';

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_location';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['type', 'location_name'], 'required'],
			[['parent_id'], 'required', 'on' => self::SCENARIO_NOT_BUILDING],
			[['parent_id', 'building'], 'required', 'on' => self::SCENARIO_ROOM],
			[['publish', 'creation_id', 'modified_id'], 'integer'],
			[['type', 'location_desc'], 'string'],
			[['location_desc', 'building', 'storage'], 'safe'],
			[['location_name'], 'string', 'max' => 128],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_NOT_BUILDING] = ['publish', 'parent_id', 'location_name', 'location_desc', 'building', 'storage'];
		return $scenarios;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'parent_id' => Yii::t('app', 'Parent'),
			'type' => Yii::t('app', 'Type'),
			'location_name' => Yii::t('app', 'Location'),
			'location_desc' => Yii::t('app', 'Description'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'parentName' => Yii::t('app', 'Parent'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'building' => Yii::t('app', 'Building'),
			'storage' => Yii::t('app', 'Storage Unit'),
			'childs' => Yii::t('app', 'Childs'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChilds($count=false, $publish=1)
	{
		if($count == false) {
			return $this->hasMany(ArchiveLocation::className(), ['parent_id' => 'id'])
				->andOnCondition([sprintf('%s.publish', ArchiveLocation::tableName()) => $publish]);
		}

		$model = ArchiveLocation::find()
			->where(['parent_id' => $this->id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$childs = $model->count();

		return $childs ? $childs : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRoomStorage($result=false, $val='id')
	{
		if($result == true)
			return ArrayHelper::map($this->roomStorage, 'storage_id', $val=='id' ? 'id' : 'storage.storage_name_i');

		return $this->hasMany(ArchiveRoomStorage::className(), ['room_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getParent()
	{
		return $this->hasOne(self::className(), ['id' => 'parent_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBuildingRltn()
	{
		return $this->hasOne(self::className(), ['id' => 'building']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\query\ArchiveLocation the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archive\models\query\ArchiveLocation(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if($this->type != 'building') {
			$this->templateColumns['parentName'] = [
				'attribute' => 'parentName',
				'label' => Inflector::humanize($this->type == 'depo' ? 'building' :'depo'),
				'value' => function($model, $key, $index, $column) {
					if($model->type == 'room')
						return isset($model->parent) ? $model->parent->location_name.', '.$model->parent->parent->location_name : '-';
					return isset($model->parent) ? $model->parent->location_name : '-';
					// return $model->parentName;
				},
			];
		}
		$this->templateColumns['type'] = [
			'attribute' => 'type',
			'value' => function($model, $key, $index, $column) {
				return self::getType($model->type);
			},
			'filter' => self::getType(),
		];
		$this->templateColumns['location_name'] = [
			'attribute' => 'location_name',
			'label' => self::getType($this->type),
			'value' => function($model, $key, $index, $column) {
				return $model->location_name;
			},
		];
		$this->templateColumns['location_desc'] = [
			'attribute' => 'location_desc',
			'value' => function($model, $key, $index, $column) {
				return $model->location_desc;
			},
		];
		if($this->type == 'room') {
			$this->templateColumns['storage'] = [
				'attribute' => 'storage',
				'label' => Yii::t('app', 'Storage'),
				'value' => function($model, $key, $index, $column) {
					return self::parseStorage($model->getRoomStorage(true, 'title'), ',');
				},
				'filter' => ArchiveStorage::getStorage(),
				'format' => 'html',
			];
		}
		if($this->type != 'room') {
			$this->templateColumns['childs'] = [
				'attribute' => 'childs',
				'label' => Inflector::humanize($this->type == 'building' ? Inflector::pluralize('depo') : Inflector::pluralize('room')),
				'value' => function($model, $key, $index, $column) {
					$childs = $model->getChilds(true);
					$controller = $this->type == 'building' ? 'depo' : 'room';
					return $childs ? Html::a($childs, ['location/'.$controller.'/manage', 'parent'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} {title}', ['count'=>$childs, 'title'=>$controller])]) : '-';
				},
				'filter' => false,
				'contentOptions' => ['class'=>'center'],
				'format' => 'html',
			];
		}
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creationDisplayname'] = [
				'attribute' => 'creationDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
					// return $model->creationDisplayname;
				},
			];
		}
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modifiedDisplayname'] = [
				'attribute' => 'modifiedDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
					// return $model->modifiedDisplayname;
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish);
				},
				'filter' => $this->filterYesNo(),
				'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
			];
		}
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getType
	 */
	public static function getType($value=null)
	{
		$items = array(
			'building' => Yii::t('app', 'Building'),
			'depo' => Yii::t('app', 'Depo'),
			'room' => Yii::t('app', 'Room'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getLocation
	 */
	public static function getLocation($data=[], $array=true) 
	{
		$model = self::find();
		if(isset($data['publish']))
			$model->andWhere(['publish' => $data['publish']]);
		if(isset($data['parent_id']))
			$model->andWhere(['parent_id' => $data['parent_id']]);
		$model->andWhere(['type' => isset($data['type']) ? $data['type'] : 'building']);

		$model = $model->orderBy('location_name ASC')->all();

		if($array == true)
			return ArrayHelper::map($model, 'id', 'location_name');

		return $model;
	}

	/**
	 * function parseStorage
	 */
	public static function parseStorage($roomStorage, $sep='li')
	{
		if(!is_array($roomStorage) || (is_array($roomStorage) && empty($roomStorage)))
			return '-';

		if($sep == 'li') {
			return Html::ul($roomStorage, ['item' => function($item, $index) {
				return Html::tag('li', Html::a($item, ['setting/storage/view', 'id'=>$index], ['title'=>$item, 'class'=>'modal-btn']));
			}, 'class'=>'list-boxed']);
		}

		return implode(', ', $roomStorage);
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->parentName = isset($this->parent) ? $this->parent->location_name : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		$this->building = isset($this->parent) ? $this->parent->parent_id : null;
		$this->storage = array_flip($this->getRoomStorage(true));
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		parent::beforeSave($insert);

		// insert new parent (building) information (in room manage)
		if(!isset($this->buildingRltn) && $this->type == 'room') {
			$model = new ArchiveLocation();
			$model->type = 'building';
			$model->location_name = $this->building;
			if($model->save())
				$this->building = $model->id;
		}

		// insert new parent (building|depo) information
		if(!isset($this->parent) && $this->type != 'building') {
			$model = new ArchiveLocation();
			if($this->type == 'room')
				$model->parent_id = $this->building;
			$model->type = $this->type == 'depo' ? 'building' : 'depo';
			$model->location_name = $this->parent_id;
			if($model->save())
				$this->parent_id = $model->id;
		}

		if(!$insert) {
			// set room storage type
			$event = new Event(['sender' => $this]);
			Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_ARCHIVE_LOCATION, $event);
		}

		return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
		
		if($insert) {
			// set room storage type
			$event = new Event(['sender' => $this]);
			Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_ARCHIVE_LOCATION, $event);
		}
	}
}
