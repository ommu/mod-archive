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
 * @property ArchiveRelatedCreator[] $creator
 * @property ArchiveRelatedRepository[] $repository
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

class Archives extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['parentTitle', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date', 'creator', 'repository'];

	public $parentTitle;
	public $levelName;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $media;
	public $creator;
	public $repository;

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
			[['media', 'creator', 'repository'], 'safe'],
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
			'parent_id' => Yii::t('app', 'Archival Parent'),
			'level_id' => Yii::t('app', 'Level of Description'),
			'title' => Yii::t('app', 'Title'),
			'code' => Yii::t('app', 'Identifier'),
			'image_type' => Yii::t('app', 'Image Type'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'media' => Yii::t('app', 'Media Type'),
			'creator' => Yii::t('app', 'Name of creator(s)'),
			'repository' => Yii::t('app', 'Repository'),
			'parentTitle' => Yii::t('app', 'Archival Parent'),
			'levelName' => Yii::t('app', 'Level of Description'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
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
		if(!Yii::$app->request->get('creatorId')) {
			$this->templateColumns['creator'] = [
				'attribute' => 'creator',
				'header' => Yii::t('app', 'Creator'),
				'value' => function($model, $key, $index, $column) {
					return Archives::parseMedia($model->getRelatedCreator(true, 'title'));
				},
				'format' => 'html',
			];
		}
		if(!Yii::$app->request->get('repositoryId')) {
			$this->templateColumns['repository'] = [
				'attribute' => 'repository',
				'value' => function($model, $key, $index, $column) {
					return Archives::parseMedia($model->getRelatedRepository(true, 'title'));
				},
				'format' => 'html',
			];
		}
		if(!Yii::$app->request->get('media')) {
			$this->templateColumns['media'] = [
				'attribute' => 'media',
				'header' => Yii::t('app', 'Media'),
				'value' => function($model, $key, $index, $column) {
					return Archives::parseMedia($model->getRelatedMedia(true, 'title'));
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
	public function getChildLevel()
	{
		$levels = ArchiveLevel::getLevel(1);
		$child = $this->level->child;
		if(!is_array($child))
			$child = [];

		$items = ArrayHelper::merge(explode(',', $this->level_id), $child);

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
	 * function parseMedia
	 */
	public static function parseMedia($relatedMedia)
	{
		if(!is_array($relatedMedia) || (is_array($relatedMedia) && empty($relatedMedia)))
			return '-';

		return Html::ul($relatedMedia, ['item' => function($item, $index) {
			return Html::tag('li', Html::a($item, ['setting/media/view', 'id'=>$index], ['title'=>$item, 'class'=>'modal-btn']));
		}, 'class'=>'list-boxed']);
	}

	/**
	 * function parseCreator
	 */
	public static function parseCreator($relatedCreator)
	{
		if(!is_array($relatedCreator) || (is_array($relatedCreator) && empty($relatedCreator)))
			return '-';

		return Html::ul($relatedCreator, ['item' => function($item, $index) {
			return Html::tag('li', Html::a($item, ['setting/creator/view', 'id'=>$index], ['title'=>$item, 'class'=>'modal-btn']));
		}, 'class'=>'list-boxed']);
	}

	/**
	 * function parseRepository
	 */
	public static function parseRepository($relatedRepository)
	{
		if(!is_array($relatedRepository) || (is_array($relatedRepository) && empty($relatedRepository)))
			return '-';

		return Html::ul($relatedRepository, ['item' => function($item, $index) {
			return Html::tag('li', Html::a($item, ['setting/repository/view', 'id'=>$index], ['title'=>$item, 'class'=>'modal-btn']));
		}, 'class'=>'list-boxed']);
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
		$oldMedia = array_flip($this->getRelatedMedia(true));
		$oldCreator = $this->getRelatedCreator(true, 'title');
		$oldRepository = $this->getRelatedRepository(true, 'title');
		$media = $this->media;
		if($this->creator)
			$creator = explode(',', $this->creator);
		if($this->repository)
			$repository = explode(',', $this->repository);

		// insert difference media
		if(is_array($media)) {
			foreach ($media as $val) {
				if(in_array($val, $oldMedia)) {
					unset($oldMedia[array_keys($oldMedia, $val)[0]]);
					continue;
				}

				$model = new ArchiveRelatedMedia();
				$model->archive_id = $this->id;
				$model->media_id = $val;
				$model->save();
			}
		}

		// drop difference media
		if(!empty($oldMedia)) {
			foreach ($oldMedia as $key => $val) {
				ArchiveRelatedMedia::findOne($key)->delete();
			}
		}

		// insert difference creator
		if(is_array($creator)) {
			foreach ($creator as $val) {
				if(in_array($val, $oldCreator)) {
					unset($oldCreator[array_keys($oldCreator, $val)[0]]);
					continue;
				}

				$creatorFind = ArchiveCreator::find()
					->select(['id'])
					->andWhere(['creator_name' => $val])
					->one();
						
				if($creatorFind != null)
					$creator_id = $creatorFind->id;
				else {
					$model = new ArchiveCreator();
					$model->creator_name = $val;
					if($model->save())
						$creator_id = $model->id;
				}

				$model = new ArchiveRelatedCreator();
				$model->archive_id = $this->id;
				$model->creator_id = $creator_id;
				$model->save();
			}
		}

		// drop difference creator
		if(!empty($oldCreator)) {
			foreach ($oldCreator as $key => $val) {
				ArchiveRelatedCreator::find()
					->select(['id'])
					->where(['archive_id'=>$this->id, 'creator_id'=>$key])
					->one()
					->delete();
			}
		}

		// insert difference repository
		if(is_array($repository)) {
			foreach ($repository as $val) {
				if(in_array($val, $oldRepository)) {
					unset($oldRepository[array_keys($oldRepository, $val)[0]]);
					continue;
				}

				$repositoryFind = ArchiveRepository::find()
					->select(['id'])
					->andWhere(['repository_name' => $val])
					->one();
						
				if($repositoryFind != null)
					$repository_id = $repositoryFind->id;
				else {
					$model = new ArchiveRepository();
					$model->repository_name = $val;
					if($model->save())
						$repository_id = $model->id;
				}

				$model = new ArchiveRelatedRepository();
				$model->archive_id = $this->id;
				$model->repository_id = $repository_id;
				$model->save();
			}
		}

		// drop difference repository
		if(!empty($oldRepository)) {
			foreach ($oldRepository as $key => $val) {
				ArchiveRelatedRepository::find()
					->select(['id'])
					->where(['archive_id'=>$this->id, 'repository_id'=>$key])
					->one()
					->delete();
			}
		}

		return true;
	}
}
