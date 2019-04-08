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
 * @property string $medium
 * @property string $image_type
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArchiveRelatedMedia[] $relatedMedia
 * @property ArchiveRelatedCreator[] $relatedCreator
 * @property ArchiveRelatedRepository[] $relatedRepository
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
use yii\helpers\ArrayHelper;
use yii\base\Event;

class Archives extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['parentTitle', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date', 'creator', 'repository'];

	public $parentTitle;
	public $levelName;
	public $creationDisplayname;
	public $modifiedDisplayname;

	public $shortCode;
	public $media;
	public $creator;
	public $repository;

	const EVENT_BEFORE_SAVE_ARCHIVES = 'BeforeSaveArchives';

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
			[['publish', 'level_id', 'title', 'shortCode'], 'required'],
			[['publish', 'sidkkas', 'parent_id', 'level_id', 'creation_id', 'modified_id'], 'integer'],
			[['title', 'image_type'], 'string'],
			[['code', 'medium', 'media', 'creator', 'repository'], 'safe'],
			[['code'], 'string', 'max' => 255],
			[['shortCode'], 'string', 'max' => 16],
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
			'publish' => Yii::t('app', 'Publication Status'),
			'sidkkas' => Yii::t('app', 'SiDKKAS'),
			'parent_id' => Yii::t('app', 'Archival Parent'),
			'level_id' => Yii::t('app', 'Level of Description'),
			'title' => Yii::t('app', 'Title'),
			'code' => Yii::t('app', 'Reference code'),
			'medium' => Yii::t('app', 'Extent and medium'),
			'image_type' => Yii::t('app', 'Image Type'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'parentTitle' => Yii::t('app', 'Archival Parent'),
			'levelName' => Yii::t('app', 'Level of Description'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'shortCode' => Yii::t('app', 'Identifier'),
			'media' => Yii::t('app', 'Media Type'),
			'creator' => Yii::t('app', 'Name of creator(s)'),
			'repository' => Yii::t('app', 'Repository'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRelatedMedia($result=false, $val='id')
	{
		if($result == true)
			return \yii\helpers\ArrayHelper::map($this->relatedMedia, 'media_id', $val=='id' ? 'id' : 'media.media_name_i');

		return $this->hasMany(ArchiveRelatedMedia::className(), ['archive_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRelatedCreator($result=false, $val='id')
	{
		if($result == true)
			return \yii\helpers\ArrayHelper::map($this->relatedCreator, 'creator_id', $val=='id' ? 'id' : 'creator.creator_name');

		return $this->hasMany(ArchiveRelatedCreator::className(), ['archive_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRelatedRepository($result=false, $val='id')
	{
		if($result == true)
			return \yii\helpers\ArrayHelper::map($this->relatedRepository, 'repository_id', $val=='id' ? 'id' : 'repository.repository_name');

		return $this->hasMany(ArchiveRelatedRepository::className(), ['archive_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getParent()
	{
		return $this->hasOne(Archives::className(), ['id' => 'parent_id']);
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
		if(!Yii::$app->request->get('parent')) {
			$this->templateColumns['parentTitle'] = [
				'attribute' => 'parentTitle',
				'header' => Yii::t('app', 'Parent'),
				'value' => function($model, $key, $index, $column) {
					return isset($model->parent) ? $model->parent->title : '-';
				},
			];
		}
		if(!Yii::$app->request->get('level')) {
			$this->templateColumns['level_id'] = [
				'attribute' => 'level_id',
				'header' => Yii::t('app', 'Level'),
				'value' => function($model, $key, $index, $column) {
					return isset($model->level) ? $model->level->title->message : '-';
					// return $model->levelName;
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
		$this->templateColumns['medium'] = [
			'attribute' => 'medium',
			'header' => Yii::t('app', 'Medium'),
			'value' => function($model, $key, $index, $column) {
				return $model->medium;
			},
		];
		if(!Yii::$app->request->get('creatorId')) {
			$this->templateColumns['creator'] = [
				'attribute' => 'creator',
				'header' => Yii::t('app', 'Creator'),
				'value' => function($model, $key, $index, $column) {
					return self::parseRelated($model->getRelatedCreator(true, 'title'), 'creator');
				},
				'format' => 'html',
			];
		}
		if(!Yii::$app->request->get('repositoryId')) {
			$this->templateColumns['repository'] = [
				'attribute' => 'repository',
				'value' => function($model, $key, $index, $column) {
					return self::parseRelated($model->getRelatedRepository(true, 'title'), 'repository');
				},
				'format' => 'html',
			];
		}
		if(!Yii::$app->request->get('media')) {
			$this->templateColumns['media'] = [
				'attribute' => 'media',
				'header' => Yii::t('app', 'Media'),
				'value' => function($model, $key, $index, $column) {
					return self::parseRelated($model->getRelatedMedia(true, 'title'));
				},
				'filter' => ArchiveMedia::getMedia(),
				'format' => 'html',
			];
		}
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
		$this->templateColumns['sidkkas'] = [
			'attribute' => 'sidkkas',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->sidkkas);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'header' => Yii::t('app', 'Status'),
				'value' => function($model, $key, $index, $column) {
					return self::getPublish($model->publish);
				},
				'filter' => self::getPublish(),
				'contentOptions' => ['class'=>'center'],
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
	 * function getPublish
	 */
	public static function getPublish($value=null)
	{
		$items = array(
			'1' => Yii::t('app', 'Published'),
			'0' => Yii::t('app', 'Draft'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getImageType
	 */
	public static function getImageType($value=null)
	{
		$items = array(
			'photo' => Yii::t('app', 'Image/Photo'),
			'text' => Yii::t('app', 'Document'),
		);

		if($value !== null) {
			if($value == '-')
				return $value;
			return $items[$value];
		} else
			return $items;
	}

	/**
	 * function getLevel
	 */
	public function getChildLevels($isNewRecord=false)
	{
		$levels = ArchiveLevel::getLevel(1);
		$child = $this->level->child;
		if(!is_array($child))
			$child = [];

		$items = $child;
		if(!$isNewRecord)
			$items = ArrayHelper::merge(explode(',', $this->level_id), $items);

		foreach ($levels as $key => $val) {
			if(!ArrayHelper::isIn($key, $items))
				ArrayHelper::remove($levels, $key);
		}

		return $levels;
	}

	/**
	 * function parseParent
	 */
	public static function parseParent($model) 
	{
		if(!isset($model->parent))
			return '-';

		$title = $model->parent->title;
		$levelName = $model->parent->level->title->message;

		$items[] = $model->getAttributeLabel('code').': '.$model->parent->code;
		$items[] = $model->getAttributeLabel('title').': '.Html::a($title, ['view', 'id'=>$model->parent_id], ['title'=>$title, 'class'=>'modal-btn']);
		$items[] = $model->getAttributeLabel('level_id').': '.Html::a($levelName, ['setting/level/view', 'id'=>$model->parent->level_id], ['title'=>$levelName, 'class'=>'modal-btn']);

		return Html::ul($items, ['encode'=>false, 'class'=>'list-boxed']);
	}

	/**
	 * function parseRelated
	 */
	public static function parseRelated($relatedMedia, $controller='media')
	{
		$items = self::getUrlFormat($relatedMedia, $controller);
		if($items == '-')
			return $items;

		return Html::ul($items, ['item' => function($item, $index) {
			return Html::tag('li', Html::a($index, $item, ['title'=>$index, 'class'=>'modal-btn']));
		}, 'class'=>'list-boxed']);
	}

	/**
	 * function getUrlFormat
	 */
	public static function getUrlFormat($array, $controller)
	{
		if(!is_array($array) || (is_array($array) && empty($array)))
			return '-';

		$items = array_flip($array);
		foreach ($items as $key => $val) {
			$items[$key] = Url::to(['setting/'.$controller.'/view', 'id'=>$val]);
		}

		return $items;
	}

	/**
	 * function getReferenceCode
	 */
	public function getReferenceCode($archive=null)
	{
		if(!$archive)
			$archive = $this;
		$codes = [];
		if(isset($archive->parent)) {
			$levelAsKey = $archive->parent->level->level_name_i;
			$codes[$levelAsKey]['id'] = $archive->parent->id;
			$codes[$levelAsKey]['code'] = $archive->parent->code;
			$codes[$levelAsKey]['shortCode'] = $archive->parent->shortCode;
			// $codes[$levelAsKey]['sidkkas'] = $archive->parent->sidkkas;
			// $codes[$levelAsKey]['publish'] = $archive->parent->publish;

			return ArrayHelper::merge($codes, $this->getReferenceCode($archive->parent));
		}

		return $codes;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->parentTitle = isset($this->parent) ? $this->parent->title : '-';
		// $this->levelName = isset($this->level) ? $this->level->title->message : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		$parentCode = $this->parent->code;
		$this->shortCode = preg_replace("/^[.-]/", '', preg_replace("/^($parentCode)/", '', $this->code));
		if(strtolower($this->level->level_name_i) == 'fond')
			$this->shortCode = preg_replace("/^[.-]/", '', preg_replace("/^(3400|23400-24)/", '', $this->shortCode));
		$this->media = array_flip($this->getRelatedMedia(true));
		$this->creator = implode(',', $this->getRelatedCreator(true, 'title'));
		$this->repository = implode(',', $this->getRelatedRepository(true, 'title'));
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
		$setting = \ommu\archive\models\ArchiveSetting::find()
			->select(['reference_code_sikn', 'reference_code_level_separator'])
			->where(['id' => 1])
			->one();

		// set code
		if(strtolower($this->level->level_name_i) == 'fond')
			$this->code = join($setting->reference_code_level_separator, [$setting->reference_code_sikn, $this->shortCode]);
		else
			$this->code = join($setting->reference_code_level_separator, [$this->parent->code, $this->shortCode]);
		
		if(!$insert) {
			// set archive media, creator and repository
			$event = new Event(['sender' => $this]);
			Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_ARCHIVES, $event);
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
			// set archive media, creator and repository
			$event = new Event(['sender' => $this]);
			Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_ARCHIVES, $event);
		}
	}
}
