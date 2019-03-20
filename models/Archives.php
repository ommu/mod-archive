<?php
/**
 * Archives
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 5 March 2019, 23:34 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 * This is the model class for table "ommu_archives".
 *
 * The followings are the available columns in table "ommu_archives":
 * @property integer $id
 * @property integer $publish
 * @property integer $sidkkas
 * @property integer $parent_id
 * @property integer $level_id
 * @property string $title
 * @property string $code
 * @property string $image_type
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArchiveRelatedMedia[] $media
 * @property ArchiveLevel $level
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\archive\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;

class Archives extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['parent_id','creation_date','creationDisplayname','modified_date','modifiedDisplayname','updated_date'];

	public $levelName;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archives';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['level_id', 'title', 'code'], 'required'],
			[['publish', 'sidkkas', 'parent_id', 'level_id', 'creation_id', 'modified_id'], 'integer'],
			[['title', 'image_type'], 'string'],
			[['code'], 'string', 'max' => 255],
			[['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchiveLevel::className(), 'targetAttribute' => ['level_id' => 'id']],
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
			'sidkkas' => Yii::t('app', 'SiDKKAS'),
			'parent_id' => Yii::t('app', 'Parent'),
			'level_id' => Yii::t('app', 'Level'),
			'title' => Yii::t('app', 'Title'),
			'code' => Yii::t('app', 'Code'),
			'image_type' => Yii::t('app', 'Image Type'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'media' => Yii::t('app', 'Media'),
			'levelName' => Yii::t('app', 'Level'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMedia($result=false)
	{
		if($result == true)
			return \yii\helpers\ArrayHelper::map($this->media, 'media_id', 'media.media_name_i');

		return $this->hasMany(ArchiveRelatedMedia::className(), ['archive_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLevel()
	{
		return $this->hasOne(ArchiveLevel::className(), ['id' => 'level_id']);
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
	 * @return \ommu\archive\models\query\Archives the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archive\models\query\Archives(get_called_class());
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
		$this->templateColumns['parent_id'] = [
			'attribute' => 'parent_id',
			'value' => function($model, $key, $index, $column) {
				return $model->parent_id;
			},
		];
		if(!Yii::$app->request->get('level')) {
			$this->templateColumns['level_id'] = [
				'attribute' => 'level_id',
				'value' => function($model, $key, $index, $column) {
					return isset($model->level) ? $model->level->title->message : '-';
				},
				'filter' => ArchiveLevel::getLevel(),
			];
		}
		$this->templateColumns['code'] = [
			'attribute' => 'code',
			'value' => function($model, $key, $index, $column) {
				return $model->code;
			},
		];
		$this->templateColumns['title'] = [
			'attribute' => 'title',
			'value' => function($model, $key, $index, $column) {
				return $model->title;
			},
		];
		$this->templateColumns['media'] = [
			'attribute' => 'media',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				return Archives::parseMedia($model->getMedia(true));
			},
			'format' => 'html',
		];
		$this->templateColumns['image_type'] = [
			'attribute' => 'image_type',
			'value' => function($model, $key, $index, $column) {
				return self::getImageType($model->image_type ? $model->image_type : '-');
			},
			'filter' => self::getImageType(),
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
		$this->templateColumns['sidkkas'] = [
			'attribute' => 'sidkkas',
			'filter' => $this->filterYesNo(),
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->sidkkas);
			},
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => $this->filterYesNo(),
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish);
				},
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
	 * function getImageType
	 */
	public static function getImageType($value=null)
	{
		$items = array(
			'photo' => Yii::t('app', 'Photo'),
			'text' => Yii::t('app', 'Text'),
		);

		if($value !== null) {
			if($value == '-')
				return $value;
			return $items[$value];
		} else
			return $items;
	}

	/**
	 * function parseMedia
	 */
	public static function parseMedia($medias, $separator=',') 
	{
		if(!is_array($medias) || (is_array($medias) && empty($medias)))
			return '-';
		
		foreach ($medias as $key => $val) {
			$media[] = Html::a($val, ['related/media/manage', 'media'=>$key], ['title'=>$val]);
		}

		return implode($separator, $media);
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->levelName = isset($this->level) ? $this->level->title->message : '-';
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
