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
 * @property ArchiveRoomStorage[] $storages
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\archive\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;

class ArchiveLocation extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['type', 'location_desc', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $creationDisplayname;
	public $modifiedDisplayname;

	const TYPE_BUILDING = 'building';
	const TYPE_DEPO = 'depo';
	const TYPE_ROOM = 'room';

	const SCENARIO_NOT_BUILDING = 'adminCreate';

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
			[['publish', 'parent_id', 'creation_id', 'modified_id'], 'integer'],
			[['type', 'location_desc'], 'string'],
			[['location_desc'], 'safe'],
			[['location_name'], 'string', 'max' => 128],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_NOT_BUILDING] = ['publish', 'parent_id', 'location_name', 'location_desc'];
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
			'storages' => Yii::t('app', 'Storages'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getStorages($count=false)
	{
		if($count == false)
			return $this->hasMany(ArchiveRoomStorage::className(), ['room_id' => 'id']);

		$model = ArchiveRoomStorage::find()
			->where(['room_id' => $this->id]);
		$storages = $model->count();

		return $storages ? $storages : 0;
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

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class'  => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if($this->type != 'building') {
			$this->templateColumns['parent_id'] = [
				'attribute' => 'parent_id',
				'value' => function($model, $key, $index, $column) {
					return isset($model->parent) ? $model->parent->location_name : '-';
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
			'header' => self::getType($this->type),
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
		if($this->type == 'room') {
			$this->templateColumns['storages'] = [
				'attribute' => 'storages',
				'value' => function($model, $key, $index, $column) {
					$storages = $model->getStorages(true);
					return Html::a($storages, ['storage/manage', 'room'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} storages', ['count'=>$storages])]);
				},
				'filter' => false,
				'contentOptions' => ['class'=>'center'],
				'format' => 'html',
			];
		}
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
	 * function getMedia
	 */
	public static function getLocation($data=[], $array=true) 
	{
		$model = self::find();
		if(isset($data['publish']))
			$model->andWhere(['publish' => $data['publish']]);
		$model->andWhere(['type' => isset($data['type']) ? $data['type'] : 'building']);

		$model = $model->orderBy('location_name ASC')->all();

		if($array == true)
			return \yii\helpers\ArrayHelper::map($model, 'id', 'location_name');

		return $model;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
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
}
