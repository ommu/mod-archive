<?php
/**
 * Archives
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
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
 * @property string $archive_type
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
 * @property ArchiveRelatedSubject[] $relatedSubject
 * @property ArchiveRelatedSubject[] $relatedFunction
 * @property ArchiveRelatedLocation[] $relatedLocation
 * @property Archives[] $archives
 * @property Archives $parent
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

	public $gridForbiddenColumn = ['parentTitle', 'media', 'creator', 'repository', 'subject', 'function', 'archive_type', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $parentTitle;
	public $levelName;
	public $creationDisplayname;
	public $modifiedDisplayname;

	public $confirmCode;
	public $shortCode;
	public $oldCode;
	public $oldConfirmCode;
	public $oldShortCode;
	public $updateCode = true;

	public $media;
	public $creator;
	public $repository;
	public $subject;
	public $function;
	public $location;
	public $group_childs;

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
			[['title', 'archive_type'], 'string'],
			[['code', 'medium', 'media', 'creator', 'repository', 'subject', 'function'], 'safe'],
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
			'archive_type' => Yii::t('app', 'Archive Type'),
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
			'subject' => Yii::t('app', 'Subject'),
			'function' => Yii::t('app', 'Function'),
			'location' => Yii::t('app', 'Location'),
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
	public function getRelatedSubject($result=false, $val='id')
	{
		if($result == true)
			return \yii\helpers\ArrayHelper::map($this->relatedSubject, 'tag_id', $val=='id' ? 'id' : 'tag.body');

		return $this->hasMany(ArchiveRelatedSubject::className(), ['archive_id' => 'id'])
			->alias('relatedSubject')
			->andOnCondition([sprintf('%s.type', 'relatedSubject') => 'subject']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRelatedFunction($result=false, $val='id')
	{
		if($result == true)
			return \yii\helpers\ArrayHelper::map($this->relatedFunction, 'tag_id', $val=='id' ? 'id' : 'tag.body');

		return $this->hasMany(ArchiveRelatedSubject::className(), ['archive_id' => 'id'])
			->alias('relatedFunction')
			->andOnCondition([sprintf('%s.type', 'relatedFunction') => 'function']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRelatedLocation($relation=true)
	{
		if($relation == false)
			return !empty($this->relatedLocation) ? $this->relatedLocation[0] : null;

		return $this->hasMany(ArchiveRelatedLocation::className(), ['archive_id' => 'id'])
			->alias('relatedLocation');
	}

	/**
	 * @param $type relation|array|count
	 * @return \yii\db\ActiveQuery
	 */
	public function getArchives($type='relation', $publish=null)
	{
		if($type == 'relation') {
			$model = $this->hasMany(Archives::className(), ['parent_id' => 'id'])
				->alias('archives');
			if($publish != null)
				return $model->andOnCondition([sprintf('%s.publish', 'archives') => $publish]);
			else
				return $model->andOnCondition(['IN', sprintf('%s.publish', 'archives'), [0,1]]);
		}

		$model = Archives::find()
			->select(['id'])
			->where(['parent_id' => $this->id]);
		if($publish != null) {
			if($publish == 0)
				$model->unpublish();
			elseif($publish == 1)
				$model->published();
			elseif($publish == 2)
				$model->deleted();
		} else
			$model->andWhere(['IN', 'publish', [0,1]]);

		if($type == 'array') {
			$model->select(['level_id', 'count(id) as group_childs'])
				->groupBy(['level_id']);
			$archives = $model->all();

			return ArrayHelper::map($archives, 'level_id', 'group_childs');
		}

		if($type == 'count') {
			$archives = $model->count();
	
			return $archives ? $archives : 0;
		}
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

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('parent')) {
			$this->templateColumns['parentTitle'] = [
				'attribute' => 'parentTitle',
				'label' => Yii::t('app', 'Parent'),
				'value' => function($model, $key, $index, $column) {
					return isset($model->parent) ? $model->parent->title : '-';
				},
				'format' => 'html',
			];
		}
		if(!Yii::$app->request->get('level')) {
			$this->templateColumns['level_id'] = [
				'attribute' => 'level_id',
				'label' => Yii::t('app', 'Level'),
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
			'format' => 'html',
		];
		$this->templateColumns['creator'] = [
			'attribute' => 'creator',
			'label' => Yii::t('app', 'Creator'),
			'value' => function($model, $key, $index, $column) {
				return self::parseRelated($model->getRelatedCreator(true, 'title'), 'creator', ', ');
			},
			'format' => 'html',
		];
		$this->templateColumns['repository'] = [
			'attribute' => 'repository',
			'value' => function($model, $key, $index, $column) {
				return self::parseRelated($model->getRelatedRepository(true, 'title'), 'repository', ', ');
			},
			'format' => 'html',
		];
		$this->templateColumns['subject'] = [
			'attribute' => 'subject',
			'value' => function($model, $key, $index, $column) {
				return self::parseSubject($model->getRelatedSubject(true, 'title'), 'subjectId', ', ');
			},
			'format' => 'html',
		];
		$this->templateColumns['function'] = [
			'attribute' => 'function',
			'value' => function($model, $key, $index, $column) {
				return self::parseSubject($model->getRelatedFunction(true, 'title'), 'functionId', ', ');
			},
			'format' => 'html',
		];
		$this->templateColumns['medium'] = [
			'attribute' => 'medium',
			'label' => Yii::t('app', 'Medium'),
			'value' => function($model, $key, $index, $column) {
				return self::parseChilds($model->childs, $model->id);
			},
			'filter' => false,
			'enableSorting' => false,
			'format' => 'html',
		];
		$this->templateColumns['media'] = [
			'attribute' => 'media',
			'label' => Yii::t('app', 'Media'),
			'value' => function($model, $key, $index, $column) {
				return self::parseRelated($model->getRelatedMedia(true, 'title'), 'media', ', ');
			},
			'filter' => ArchiveMedia::getMedia(),
			'format' => 'html',
		];
		$this->templateColumns['archive_type'] = [
			'attribute' => 'archive_type',
			'value' => function($model, $key, $index, $column) {
				return self::getArchiveType($model->archive_type ? $model->archive_type : '-');
			},
			'filter' => self::getArchiveType(),
		];
		if(ArchiveSetting::getInfo('fond_sidkkas')) {
			if(!Yii::$app->request->get('id')) {
				$this->templateColumns['sidkkas'] = [
					'attribute' => 'sidkkas',
					'value' => function($model, $key, $index, $column) {
						return $this->filterYesNo($model->sidkkas);
					},
					'filter' => $this->filterYesNo(),
					'contentOptions' => ['class'=>'center'],
				];
			}
		}
		$this->templateColumns['location'] = [
			'attribute' => 'location',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->location);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
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
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'label' => Yii::t('app', 'Status'),
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
	 * function getChilds
	 */
	public function getChilds()
	{
		if(strtolower($this->level->level_name_i) == 'item')
			return [];

		$childs = $this->getArchives('array');
		if(empty($childs))
			return [];

		// $archives = self::find()
		// 	->select(['id', 'level_id'])
		// 	->where(['parent_id' => $this->id])
		// 	->andWhere(['IN', 'publish', [0,1]])
		// 	->all();

		// if(!empty($archives)) {
		// 	foreach ($archives as $archive) {
		// 		if(strtolower($archive->level->level_name_i) != 'item') {
		// 			$childArchives = $archive->getChilds();
		// 			if(!empty($childArchives)) {
		// 				foreach ($childArchives as $key => $val) {
		// 					if(array_key_exists($key, $childs))
		// 						$childs[$key] = $childs[$key] + $val;
		// 					else
		// 						$childs[$key] = $val;
		// 				}
		// 			}
		// 		}
		// 	}
		// }

		if($this->medium)
			return ArrayHelper::merge($childs, [0=>$this->medium]);

		return $childs;
	}

	/**
	 * function getReferenceCode
	 */
	public function getReferenceCode($result=false)
	{
		if($result == true)
			return ArrayHelper::map($this->referenceCode, 'level', 'confirmCode');

		$codes = [];
		$levelAsKey = $this->level->level_name_i;
		$codes[$levelAsKey]['id'] = $this->id;
		$codes[$levelAsKey]['level'] = $levelAsKey;
		$codes[$levelAsKey]['code'] = $this->code;
		$codes[$levelAsKey]['confirmCode'] = $this->confirmCode;
		$codes[$levelAsKey]['shortCode'] = $this->shortCode;
		if(isset($this->parent))
			$codes = ArrayHelper::merge($this->parent->getReferenceCode(), $codes);

		return $codes;
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
	 * function getArchiveType
	 */
	public static function getArchiveType($value=null)
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
	 * function parseParent
	 */
	public static function parseParent($model) 
	{
		if(!isset($model->parent))
			return Yii::$app->request->isAjax ? '-' : '<div id="tree" class="aciTree"></div>';

		$title = self::htmlHardDecode($model->parent->title);
		$levelName = $model->parent->level->title->message;

		$items[] = $model->getAttributeLabel('level_id').': '.Html::a($levelName, ['setting/level/view', 'id'=>$model->parent->level_id], ['title'=>$levelName, 'class'=>'modal-btn']);
		$items[] = Yii::t('app', '{level} Code: {code}', ['level'=>$levelName, 'code'=>$model->parent->code]);
		$items[] = $model->getAttributeLabel('title').': '.Html::a($title, ['view', 'id'=>$model->parent_id], ['title'=>$title, 'class'=>'modal-btn']);

		if(Yii::$app->request->isAjax)
			return Html::ul($items, ['encode'=>false, 'class'=>'list-boxed']);
		return Html::ul($items, ['encode'=>false, 'class'=>'list-boxed']).'<hr/><div id="tree" class="aciTree"></div>';
	}

	/**
	 * function parseRelated
	 */
	public static function parseRelated($relatedMedia, $controller='media', $sep='li')
	{
		if(!is_array($relatedMedia) || (is_array($relatedMedia) && empty($relatedMedia)))
			return '-';

		$items = self::getRelatedUrl($relatedMedia, $controller, true);

		if($sep == 'li') {
			return Html::ul($items, ['item' => function($item, $index) {
				return Html::tag('li', $item);
			}, 'class'=>'list-boxed']);
		}

		return implode($sep, $items);
	}

	/**
	 * function getRelatedUrl
	 */
	public static function getRelatedUrl($relates, $controller, $hyperlink=false)
	{
		$items = [];
		foreach ($relates as $key => $val) {
			if($hyperlink)
				$items[$val] = Html::a($val, ['setting/'.$controller.'/view', 'id'=>$key], ['title'=>$val, 'class'=>'modal-btn']);
			else
				$items[$val] = Url::to(['setting/'.$controller.'/view', 'id'=>$key]);
		}

		return $items;
	}

	/**
	 * function parseSubject
	 */
	public static function parseSubject($relatedSubject, $attr='subjectId', $sep='li')
	{
		if(!is_array($relatedSubject) || (is_array($relatedSubject) && empty($relatedSubject)))
			return '-';

		$items = [];
		foreach ($relatedSubject as $key => $val) {
			$items[$val] = Html::a($val, ['admin/manage', $attr=>$key], ['title'=>$val]);
		}

		if($sep == 'li') {
			return Html::ul($items, ['item' => function($item, $index) {
				return Html::tag('li', $item);
			}, 'class'=>'list-boxed']);
		}

		return implode($sep, $items);
	}

	/**
	 * function parseChilds
	 */
	public static function parseChilds($childs, $id, $sep='li')
	{
		if(empty($childs))
			return '-';

		$levels = ArchiveLevel::getLevel();
		$return = [];
		$i=0;
		foreach ($childs as $key => $val) {
			$i++;
			$title = $val." ".$levels[$key];
			$return[] = $i == 1 ? Html::a($title, ['admin/manage', 'id'=>$id], ['title'=>$title]) : $title;
		}

		if($sep == 'li')
			return Html::ul($return, ['encode'=>false, 'class'=>'list-boxed']);

		return implode(', ', $childs);
	}

	/**
	 * function parseLocation
	 */
	public static function parseLocation($model)
	{
		if($model == null)
			return '-';

		if(isset($model->rack))
			$items[] = Yii::t('app', 'Rack: {rack}', ['rack'=>$model->rack->location_name]);
		if(isset($model->room))
			$items[] = Yii::t('app', 'Location: {room}, {depo}, {building}', ['room'=>$model->room->location_name, 'depo'=>$model->depo->location_name, 'building'=>$model->building->location_name]);
		if(isset($model->storage))
			$items[] = Yii::t('app', 'Storage: {storage-name}', ['storage-name'=>$model->storage->storage_name_i]);
		if($model->weight != '')
			$items[] = Yii::t('app', 'Weight: {weight}', ['weight'=>$model->weight]);
		if($model->location_desc != '')
			$items[] = Yii::t('app', 'Noted: {location-desc}', ['location-desc'=>$model->location_desc]);

		return Html::ul($items, ['encode'=>false, 'class'=>'list-boxed']);
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		$setting = \ommu\archive\models\ArchiveSetting::find()
			->select(['maintenance_mode'])
			->where(['id' => 1])
			->one();

		parent::afterFind();

		// $this->parentTitle = isset($this->parent) ? $this->parent->title : '-';
		// $this->levelName = isset($this->level) ? $this->level->title->message : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		$this->code = preg_replace("/^[.-]/", '', preg_replace("/^(3400|23400-24)/", '', $this->code));
		$this->oldCode = $this->code;
		$parentCode = $this->parent->code;
		if($setting->maintenance_mode) {
			if($this->parent->code == $this->parent->confirmCode)
				$parentCode = preg_replace("/[.-]$/", '', join('.', $this->getReferenceCode(true)));
			$confirmCode = preg_replace("/^[.-]/", '', preg_replace("/^($parentCode)/", '', $this->code));
			$parentConfirmCode = $this->parent->confirmCode;
			if(preg_match("/^($parentConfirmCode)/", $confirmCode)) {
				$shortCodeStatus = false;
				$this->confirmCode = $confirmCode;
			} else {
				$shortCodeStatus = true;
				if(count(explode('.', $confirmCode)) == 1)
					$this->confirmCode = join('.', [$parentConfirmCode, $confirmCode]);
				else
					$this->confirmCode = $confirmCode;
			}
			$this->shortCode = $shortCodeStatus ? $confirmCode : preg_replace("/^[.-]/", '', preg_replace("/^($parentConfirmCode)/", '', $this->confirmCode));
		} else
			$this->shortCode = preg_replace("/^[.-]/", '', preg_replace("/^($parentCode)/", '', $this->code));

		$this->oldConfirmCode = $this->confirmCode;
		$this->oldShortCode = $this->shortCode;

		$this->media = array_flip($this->getRelatedMedia(true));
		$this->creator = implode(',', $this->getRelatedCreator(true, 'title'));
		$this->repository =  array_flip($this->getRelatedRepository(true));
		$this->subject =  implode(',', $this->getRelatedSubject(true, 'title'));
		$this->function =  implode(',', $this->getRelatedFunction(true, 'title'));
		$this->location = $this->getRelatedLocation(false) != null ? 1 : 0;
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
			->select(['maintenance_mode'])
			->where(['id' => 1])
			->one();

		parent::beforeSave($insert);

		// set code
		if($this->updateCode == true) {
			if(strtolower($this->level->level_name_i) == 'fond')
				$this->code = $this->shortCode;
			else {
				if($setting->maintenance_mode) {
					if(count(explode('.', $this->shortCode)) == 1)
						$this->code = join('.', [$this->parent->confirmCode, $this->shortCode]);
					else
						$this->code = $this->shortCode;
				} else
					$this->code = join('.', [$this->parent->code, $this->shortCode]);
			}
			// $this->code = strtolower($this->level->level_name_i) == 'fond' ? 
			// 	$this->shortCode : 
			// 	($setting->maintenance_mode ? 
			// 		join('.', [$this->parent->confirmCode, $this->shortCode]) :
			// 		join('.', [$this->parent->code, $this->shortCode]));
		}
	
		// replace code
		if(array_key_exists('code', $this->dirtyAttributes) && $this->dirtyAttributes['code'] != $this->oldCode) {
			$models = self::find()
				->select(['id', 'parent_id', 'level_id', 'code'])
				->where(['parent_id'=>$this->id])
				->all();
			if(!empty($models)) {
				foreach ($models as $model) {
					if($setting->maintenance_mode)
						$model->parent->confirmCode = $this->dirtyAttributes['code'];
					else
						$model->parent->code = $this->dirtyAttributes['code'];
					$model->updateCode = true;
					$model->update(false);
				}
			}
		}
		
		if(!$insert) {
			// set archive media, creator repository, subject and function
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
			// set archive media, creator repository, subject and function
			$event = new Event(['sender' => $this]);
			Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_ARCHIVES, $event);

		} else {
			// update sidkkas status
			if(array_key_exists('sidkkas', $changedAttributes) && $changedAttributes['sidkkas'] != $this->sidkkas) {
				$models = self::find()
					->select(['id', 'sidkkas', 'parent_id', 'level_id', 'code'])
					->where(['parent_id'=>$this->id])
					->all();
				if(!empty($models)) {
					foreach ($models as $model) {
						$model->updateCode = false;
						$model->sidkkas = $this->sidkkas;
						$model->update(false);
					}
				}
			}

			// delete archive childs
			if(array_key_exists('publish', $changedAttributes) && $changedAttributes['publish'] != $this->publish && $this->publish == 2) {
				$models = self::find()
					->select(['id', 'publish'])
					->where(['parent_id'=>$this->id])
					->all();
				if(!empty($models)) {
					foreach ($models as $model) {
						$model->updateCode = false;
						$model->publish = 2;
						$model->update(false);
					}
				}
			}
		}
	}
}
