<?php
/**
 * ArchiveStorage
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 April 2019, 17:00 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 * This is the model class for table "ommu_archive_storage".
 *
 * The followings are the available columns in table "ommu_archive_storage":
 * @property integer $id
 * @property integer $publish
 * @property integer $parent_id
 * @property integer $storage_name
 * @property integer $storage_desc
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArchiveRoomStorage[] $rooms
 * @property SourceMessage $title
 * @property SourceMessage $description
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\archive\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use app\models\SourceMessage;
use ommu\users\models\Users;

class ArchiveStorage extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['storage_desc_i', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $storage_name_i;
	public $storage_desc_i;
	public $parentName;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_storage';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['storage_name_i'], 'required'],
			[['publish', 'parent_id', 'storage_name', 'storage_desc', 'creation_id', 'modified_id'], 'integer'],
			[['storage_name_i', 'storage_desc_i'], 'string'],
			[['storage_desc_i'], 'safe'],
			[['storage_name_i'], 'string', 'max' => 64],
			[['storage_desc_i'], 'string', 'max' => 128],
		];
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
			'storage_name' => Yii::t('app', 'Storage'),
			'storage_desc' => Yii::t('app', 'Description'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'storage_name_i' => Yii::t('app', 'Storage'),
			'storage_desc_i' => Yii::t('app', 'Description'),
			'rooms' => Yii::t('app', 'Rooms'),
			'parentName' => Yii::t('app', 'Parent'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRooms($count=false)
	{
		if($count == false)
			return $this->hasMany(ArchiveRoomStorage::className(), ['storage_id' => 'id']);

		$model = ArchiveRoomStorage::find()
			->alias('t')
			->where(['t.storage_id' => $this->id]);
		$rooms = $model->count();

		return $rooms ? $rooms : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getParent()
	{
		return $this->hasOne(ArchiveStorage::className(), ['id' => 'parent_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'storage_name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDescription()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'storage_desc']);
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
	 * @return \ommu\archive\models\query\ArchiveStorage the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archive\models\query\ArchiveStorage(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		if(!$this->hasMethod('search'))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['parentName'] = [
			'attribute' => 'parentName',
			'value' => function($model, $key, $index, $column) {
				return isset($model->parent) ? $model->parent->storage_name_i : '-';
				// return $model->parentName;
			},
		];
		$this->templateColumns['storage_name_i'] = [
			'attribute' => 'storage_name_i',
			'value' => function($model, $key, $index, $column) {
				return $model->storage_name_i;
			},
		];
		$this->templateColumns['storage_desc_i'] = [
			'attribute' => 'storage_desc_i',
			'value' => function($model, $key, $index, $column) {
				return $model->storage_desc_i;
			},
		];
		$this->templateColumns['rooms'] = [
			'attribute' => 'rooms',
			'value' => function($model, $key, $index, $column) {
				$rooms = $model->getRooms(true);
				return Html::a($rooms, ['location/room/manage', 'storage'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} rooms', ['count'=>$rooms]), 'data-pjax'=>0]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getStorage
	 */
	public static function getStorage($publish=null, $array=true) 
	{
		$model = self::find()->alias('t')
			->select(['t.id', 't.storage_name']);
		$model->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.storage_name=title.id');
		if($publish != null)
			$model->andWhere(['t.publish' => $publish]);

		$model = $model->orderBy('title.message ASC')->all();

		if($array == true)
			return \yii\helpers\ArrayHelper::map($model, 'id', 'storage_name_i');

		return $model;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->storage_name_i = isset($this->title) ? $this->title->message : '';
		$this->storage_desc_i = isset($this->description) ? $this->description->message : '';
		// $this->parentName = isset($this->parent) ? $this->parent->storage_name_i : '-';
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

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		$module = strtolower(Yii::$app->controller->module->id);
		$controller = strtolower(Yii::$app->controller->id);
		$action = strtolower(Yii::$app->controller->action->id);

		$location = Inflector::slug($module.' '.$controller);

		if(parent::beforeSave($insert)) {
			if($insert || (!$insert && !$this->storage_name)) {
				$storage_name = new SourceMessage();
				$storage_name->location = $location.'_title';
				$storage_name->message = $this->storage_name_i;
				if($storage_name->save())
					$this->storage_name = $storage_name->id;

			} else {
				$storage_name = SourceMessage::findOne($this->storage_name);
				$storage_name->message = $this->storage_name_i;
				$storage_name->save();
			}

			if($insert || (!$insert && !$this->storage_desc)) {
				$storage_desc = new SourceMessage();
				$storage_desc->location = $location.'_description';
				$storage_desc->message = $this->storage_desc_i;
				if($storage_desc->save())
					$this->storage_desc = $storage_desc->id;

			} else {
				$storage_desc = SourceMessage::findOne($this->storage_desc);
				$storage_desc->message = $this->storage_desc_i;
				$storage_desc->save();
			}

		}
		return true;
	}
}
