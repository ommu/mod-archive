<?php
/**
 * Archives
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
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
 * @property string $archive_date
 * @property string $archive_file
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArchiveGrid $grid
 * @property ArchiveLocations[] $locations
 * @property ArchiveRelatedCreator[] $creators
 * @property ArchiveRelatedMedia[] $medias
 * @property ArchiveRelatedRepository[] $repositories
 * @property ArchiveRelatedSubject[] $subjects
 * @property ArchiveRelatedSubject[] $functions
 * @property ArchiveViews[] $views
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
use app\models\Users;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use thamtech\uuid\helpers\UuidHelper;
use yii\base\Event;
use yii\helpers\Inflector;
use ommu\archiveLocation\models\ArchiveLocations;
use app\models\SourceMessage;

class Archives extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = ['archive_type', 'archive_file', 'creation_date', 'modified_date', 'updated_date', 'creator', 'repository', 'subject', 'function', 'parentTitle', 'creationDisplayname', 'modifiedDisplayname', 'oView', 'oFavourite'];

	public $old_archive_file;
	public $isFond = true;
	public $isLuring = true;
	public $isLocation = true;

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
	public $preview;
	public $location;
	public $group_childs;
	public $backToManage;

	public $parentTitle;
	public $levelName;
	public $creationDisplayname;
	public $modifiedDisplayname;
    public $oView;
    public $oFile;
    public $oFavourite;

    public $creatorId;
    public $repositoryId;
    public $subjectId;
    public $functionId;

    public $rackId;
    public $roomId;
    public $depoId;
    public $buildingId;

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
			[['publish', 'sidkkas', 'parent_id', 'level_id', 'creation_id', 'modified_id', 'backToManage'], 'integer'],
			[['title', 'archive_type', 'archive_date'], 'string'],
			[['code', 'medium', 'archive_type', 'archive_date', 'archive_file', 'media', 'creator', 'repository', 'subject', 'function', 'backToManage'], 'safe'],
			[['code'], 'string', 'max' => 255],
			[['archive_date'], 'string', 'max' => 64],
			[['shortCode'], 'string', 'max' => 32],
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
			'parent_id' => Yii::t('app', 'Parent'),
			'level_id' => Yii::t('app', 'Level of Description'),
			'title' => Yii::t('app', 'Title'),
			'code' => Yii::t('app', 'Reference code'),
			'medium' => Yii::t('app', 'Medium'),
			'archive_type' => Yii::t('app', 'Archive Type'),
			'archive_date' => Yii::t('app', 'Archive Date'),
			'archive_file' => Yii::t('app', 'Archive File'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'old_archive_file' => Yii::t('app', 'Old Filename'),
			'parentTitle' => Yii::t('app', 'Parent'),
			'levelName' => Yii::t('app', 'Level of Description'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'shortCode' => Yii::t('app', 'Identifier'),
			'media' => Yii::t('app', 'Media Type'),
			'creator' => Yii::t('app', 'Name of creator(s)'),
			'repository' => Yii::t('app', 'Repository'),
			'subject' => Yii::t('app', 'Subject'),
			'function' => Yii::t('app', 'Function'),
			'preview' => Yii::t('app', 'Preview'),
			'location' => Yii::t('app', 'Location'),
			'published_date' => Yii::t('app', 'Published Date'),
			'backToManage' => Yii::t('app', 'Back to Manage'),
            'oView' => Yii::t('app', 'Views'),
			'oFile' => Yii::t('app', 'Luring File'),
			'oFavourite' => Yii::t('app', 'Bookmark'),
		];
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrid()
    {
        return $this->hasOne(ArchiveGrid::className(), ['id' => 'id']);
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMedias($result=false, $val='id')
	{
        if ($result == true) {
            return \yii\helpers\ArrayHelper::map($this->medias, 'media_id', $val=='id' ? 'id' : 'mediaTitle.message');
        }

		return $this->hasMany(ArchiveRelatedMedia::className(), ['archive_id' => 'id'])
            ->select(['id', 'archive_id', 'media_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreators($result=false, $val='id')
	{
        if ($result == true) {
            return \yii\helpers\ArrayHelper::map($this->creators, 'creator_id', $val=='id' ? 'id' : 'creator.creator_name');
        }

		return $this->hasMany(ArchiveRelatedCreator::className(), ['archive_id' => 'id'])
            ->select(['id', 'archive_id', 'creator_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRepositories($result=false, $val='id')
	{
        if ($result == true) {
            return \yii\helpers\ArrayHelper::map($this->repositories, 'repository_id', $val=='id' ? 'id' : 'repository.repository_name');
        }

		return $this->hasMany(ArchiveRelatedRepository::className(), ['archive_id' => 'id'])
            ->select(['id', 'archive_id', 'repository_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSubjects($result=false, $val='id')
	{
        if ($result == true) {
            return \yii\helpers\ArrayHelper::map($this->subjects, 'tag_id', $val=='id' ? 'id' : 'tag.body');
        }

		return $this->hasMany(ArchiveRelatedSubject::className(), ['archive_id' => 'id'])
			->alias('subjects')
            ->select(['id', 'archive_id', 'tag_id'])
			->andOnCondition([sprintf('%s.type', 'subjects') => 'subject']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFunctions($result=false, $val='id')
	{
        if ($result == true) {
            return \yii\helpers\ArrayHelper::map($this->functions, 'tag_id', $val=='id' ? 'id' : 'tag.body');
        }

		return $this->hasMany(ArchiveRelatedSubject::className(), ['archive_id' => 'id'])
			->alias('functions')
            ->select(['id', 'archive_id', 'tag_id'])
			->andOnCondition([sprintf('%s.type', 'functions') => 'function']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLocations($relation=true)
	{
        if ($relation == false) {
            return !empty($this->locations) ? $this->locations[0] : null;
        }

		return $this->hasMany(ArchiveLocations::className(), ['archive_id' => 'id'])
			->alias('locations')
            ->select(['id', 'archive_id', 'room_id', 'rack_id', 'storage_id']);
	}

	/**
	 * @param $type relation|array|count
	 * @return \yii\db\ActiveQuery
	 */
	public function getArchives($type='relation', $publish=null)
	{
        if ($type == 'relation') {
			$model = $this->hasMany(Archives::className(), ['parent_id' => 'id'])
                ->alias('t');
	        if ($publish != null) {
                $model->andOnCondition([sprintf('%s.publish', 't') => $publish]);
            } else {
                $model->andOnCondition(['IN', sprintf('%s.publish', 't'), [0,1]]);
            }

            return $model;
        }

		$model = Archives::find()
            ->alias('t')
			->select(['t.id'])
			->where(['t.parent_id' => $this->id]);
        if ($publish != null) {
            if ($publish == 0) {
                $model->unpublish();
            } else if ($publish == 1) {
                $model->published();
            } else if ($publish == 2) {
				$model->deleted();
            }
		} else {
            $model->andWhere(['IN', 't.publish', [0,1]]);
        }

        if ($type == 'array') {
			$model->select(['t.level_id', 'count(id) as group_childs'])
				->groupBy(['t.level_id']);
			$archives = $model->all();

			return ArrayHelper::map($archives, 'level_id', 'group_childs');
		}

        if ($type == 'count') {
			$archives = $model->count();
	
			return $archives ? $archives : 0;
		}
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getParent()
	{
		return $this->hasOne(Archives::className(), ['id' => 'parent_id'])
            ->select(['id', 'parent_id', 'level_id', 'title', 'code']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLevel()
	{
		return $this->hasOne(ArchiveLevel::className(), ['id' => 'level_id'])
            ->select(['id', 'level_name', 'child', 'field']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLevelTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'level_name'])
            ->select(['id', 'message'])
            ->via('level');
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id'])
            ->select(['user_id', 'displayname']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id'])
            ->select(['user_id', 'displayname']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getViews($count=false, $publish=1)
	{
        if ($count == false) {
            return $this->hasMany(ArchiveViews::className(), ['archive_id' => 'id'])
                ->alias('views')
                ->andOnCondition([sprintf('%s.publish', 'views') => $publish]);
        }

		$model = ArchiveViews::find()
            ->alias('t')
            ->where(['t.archive_id' => $this->id]);
        if ($publish == 0) {
            $model->unpublish();
        } else if ($publish == 1) {
            $model->published();
        } else if ($publish == 2) {
            $model->deleted();
        }
		$views = $model->sum('views');

		return $views ? $views : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFavourites()
	{
        $user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

        return $this->hasMany(ArchiveFavourites::className(), ['archive_id' => 'id'])
            ->alias('favourite')
            ->select(['id', 'publish', 'archive_id', 'user_id'])
            ->andOnCondition([sprintf('%s.user_id', 'favourite') => $user_id]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFavourite()
	{
        $favourite = $this->favourites[0];
        if ($favourite == null) {
            $return= [
                'status' => false,
                'id' => 0,
            ];
        } else {
            $return= [
                'status' => $favourite->publish == 1 ? true : false,
                'id' => $favourite->id,
            ];
        }

        return $return;
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

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['parentTitle'] = [
			'attribute' => 'parentTitle',
			'label' => Yii::t('app', 'Parent'),
			'value' => function($model, $key, $index, $column) {
				return isset($model->parent) ? $model->parent->title : '-';
			},
			'format' => 'html',
			'visible' => !Yii::$app->request->get('parent') ? true : false,
		];
		$this->templateColumns['level_id'] = [
			'attribute' => 'level_id',
			'label' => Yii::t('app', 'Level'),
			'value' => function($model, $key, $index, $column) {
				return isset($model->levelTitle) ? $model->levelTitle->message : '-';
				// return $model->levelName;
			},
			'filter' => ArchiveLevel::getLevel(),
			'visible' => $this->isFond || (!$this->isFond && Yii::$app->request->get('level') && Yii::$app->request->get('data') == 'yes') ? false : true,
		];
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
				return self::parseRelated($model->getCreators(true, 'title'), 'creator', ', ');
			},
			'format' => 'html',
		];
		$this->templateColumns['archive_date'] = [
			'attribute' => 'archive_date',
			'value' => function($model, $key, $index, $column) {
				return $model->archive_date;
			},
		];
		$this->templateColumns['medium'] = [
			'attribute' => 'medium',
			'label' => Yii::t('app', 'Child & Medium'),
			'value' => function($model, $key, $index, $column) {
                if (strtolower($model->levelTitle->message) == 'item') {
                    return $model->medium ? $model->medium : '-';
                }
				return self::parseChilds($model->getChilds(['sublevel' => false, 'back3nd' => true]), $model->id, ', ');
			},
			'filter' => false,
			'enableSorting' => false,
			'contentOptions' => ['class' => 'text-nowrap'],
			'format' => 'raw',
		];
		$this->templateColumns['media'] = [
			'attribute' => 'media',
			'label' => Yii::t('app', 'Media'),
			'value' => function($model, $key, $index, $column) {
				return self::parseFilter($model->getMedias(true, 'title'), 'media', ', ');
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
		$this->templateColumns['subject'] = [
			'attribute' => 'subject',
			'value' => function($model, $key, $index, $column) {
				return self::parseFilter($model->getSubjects(true, 'title'), 'subjectId', ', ');
			},
			'format' => 'html',
		];
		$this->templateColumns['function'] = [
			'attribute' => 'function',
			'value' => function($model, $key, $index, $column) {
				return self::parseFilter($model->getFunctions(true, 'title'), 'functionId', ', ');
			},
			'format' => 'html',
		];
		$this->templateColumns['repository'] = [
			'attribute' => 'repository',
			'value' => function($model, $key, $index, $column) {
				return self::parseRelated($model->getRepositories(true, 'title'), 'repository', ', ');
			},
			'format' => 'html',
		];
		$this->templateColumns['archive_file'] = [
			'attribute' => 'archive_file',
			'value' => function($model, $key, $index, $column) {
                if (!$model->archive_file) {
                    return '-';
                }

				$extension = pathinfo($model->old_archive_file, PATHINFO_EXTENSION);
				$setting = $model->getSetting(['image_type', 'document_type', 'maintenance_mode', 'maintenance_image_path', 'maintenance_document_path']);
				$imageFileType = $model->formatFileType($setting->image_type);
				$documentFileType = $model->formatFileType($setting->document_type);

                if ($model->isNewFile) {
                    $uploadPath = join('/', [$model::getUploadPath(false), $model->id]);
                } else {
                    if (in_array($extension, $imageFileType)) {
                        $uploadPath = join('/', [$model::getUploadPath(false), $setting->maintenance_image_path]);
                    }
                    if (in_array($extension, $documentFileType)) {
                        $uploadPath = join('/', [$model::getUploadPath(false), $setting->maintenance_document_path]);
                    }
				}

				return Html::a($model->archive_file, Url::to(join('/', ['@webpublic', $uploadPath, $model->archive_file])), ['title' => $model->archive_file, 'data-pjax' => 0, 'target' => '_blank']);
			},
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
		$this->templateColumns['oView'] = [
			'attribute' => 'oView',
			'value' => function($model, $key, $index, $column) {
				// $views = $model->getViews(true);
                $views = $model->grid->view;
				return Html::a($views, ['view/admin/manage', 'archive' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} views', ['count' => $views]), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['oFavourite'] = [
			'attribute' => 'oFavourite',
			'value' => function($model, $key, $index, $column) {
				// $views = $model->getViews(true);
                $favourites = $model->grid->favourite;
				return Html::a($favourites, ['favourite/admin/manage', 'archive' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} bookmarks', ['count' => $favourites]), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'visible' => !$this->isFond ? true : false,
		];
		$this->templateColumns['oFile'] = [
			'attribute' => 'oFile',
			'label' => Yii::t('app', 'Luring'),
			'value' => function($model, $key, $index, $column) {
                $senaraiFile = Html::a(Yii::t('app', 'Document'), ['luring/admin/create', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Generate Senarai Luring'), 'class' => 'modal-btn']);
                $oFile = $model->grid->luring;
                if ($oFile) {
                    $senaraiFile = Html::a('<span class="glyphicon glyphicon-ok"></span>', ['luring/admin/manage', 'archive' => $model->primaryKey], ['title' => Yii::t('app', 'View Senarai Luring'), 'data-pjax' => 0]);
                }
				return $senaraiFile;
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'visible' => $this->isFond && $this->isLuring ? true : false,
		];
		$this->templateColumns['location'] = [
			'attribute' => 'location',
			'value' => function($model, $key, $index, $column) {
                $location = $model->getLocations(false) != null ? 1 : 0;
				return $this->filterYesNo($location);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'visible' => !$this->isFond && $this->isLocation ? true : false,
		];
		$this->templateColumns['preview'] = [
			'attribute' => 'preview',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->preview);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'visible' => !$this->isFond ? true : false,
		];
        if (ArchiveSetting::getInfo('fond_sidkkas')) {
			$this->templateColumns['sidkkas'] = [
				'attribute' => 'sidkkas',
				'value' => function($model, $key, $index, $column) {
					return $this->filterYesNo($model->sidkkas);
				},
				'filter' => $this->filterYesNo(),
				'contentOptions' => ['class' => 'text-center'],
				'visible' => !Yii::$app->request->get('id') ? true : false,
			];
		}
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'label' => Yii::t('app', 'Status'),
			'value' => function($model, $key, $index, $column) {
				return self::getPublish($model->publish);
			},
			'filter' => self::getPublish(),
			'contentOptions' => ['class' => 'text-center'],
			'visible' => !Yii::$app->request->get('trash') ? true : false,
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * function getSetting
	 */
	public function getSetting($field=[])
	{
        if (empty($field)) {
            $field = ['fond_sidkkas', 'maintenance_mode', 'reference_code_sikn', 'reference_code_separator', 'image_type', 'document_type'];
        }

		$setting = ArchiveSetting::find()
			->select($field)
			->where(['id' => 1])
			->one();
		
		return $setting;
	}

	/**
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getUploadPath($returnAlias=true)
	{
		return ($returnAlias ? Yii::getAlias('@public/archive') : 'archive');
	}

	/**
	 * function getLevel
	 */
	public function getChildLevels($isNewRecord=false)
	{
		$levels = ArchiveLevel::getLevel(1);
		$child = $this->level->child;
        if (!is_array($child)) {
            $child = [];
        }

		$items = $child;
        if (!$isNewRecord) {
            $items = ArrayHelper::merge(explode(',', $this->level_id), $items);
        }

		foreach ($levels as $key => $val) {
            if (!ArrayHelper::isIn($key, $items)) {
                ArrayHelper::remove($levels, $key);
            }
		}

		return $levels;
	}

	/**
	 * function getChilds
	 * @param sublevel true|false default:false
	 * @param back3nd true|false default:true
	 */
	public function getChilds($param=[])
	{
        if (empty($this->level->child)) {
            return [];
        }

		$setting = ArchiveSetting::find()
			->select(['medium_sublevel'])
			->where(['id' => 1])
			->one();

		$sublevel = $setting->medium_sublevel;
		$back3nd = 1;
        if (isset($param['sublevel'])) {
            $sublevel = $param['sublevel'] ? 1 : 0;
        }
        if (isset($param['back3nd'])) {
            $back3nd = $param['back3nd'] ? 1 : 0;
        }

		$childs = $this->getArchives('array', $back3nd ? null : 1);
        if (empty($childs)) {
            return [];
        }

        if ($sublevel) {
			$archives = self::find()
				->select(['id', 'level_id']);
            if ($back3nd) {
                $archives->andWhere(['IN', 'publish', [0,1]]);
            } else {
                $archives->andWhere(['publish' => 1]);
            }
			$archives = $archives->andWhere(['parent_id' => $this->id])
				->all();
	
            if (!empty($archives)) {
				foreach ($archives as $archive) {
                    if (!empty($archive->level->child)) {
						$childArchives = $archive->getChilds(['sublevel' => $sublevel, 'back3nd' => $back3nd]);
                        if (!empty($childArchives)) {
							foreach ($childArchives as $key => $val) {
                                if (array_key_exists($key, $childs)) {
                                    $childs[$key] = $childs[$key] + $val;
                                } else {
                                    $childs[$key] = $val;
                                }
							}
						}
					}
				}
			}
		}

        if ($this->medium && empty($this->level->child)) {
            return ArrayHelper::merge($childs, [0 => $this->medium]);
        }

		return $childs;
	}

	/**
	 * function getReferenceCode
	 */
	public function getReferenceCode($result=false)
	{
        if ($result == true) {
            return ArrayHelper::map($this->referenceCode, 'level', 'confirmCode');
        }

		$codes = [];
		$levelAsKey = $this->levelTitle->message;
		$codes[$levelAsKey]['id'] = $this->id;
		$codes[$levelAsKey]['level'] = $levelAsKey;
		$codes[$levelAsKey]['code'] = $this->code;
		$codes[$levelAsKey]['confirmCode'] = $this->confirmCode;
		$codes[$levelAsKey]['shortCode'] = $this->shortCode;
        if (isset($this->parent)) {
            $codes = ArrayHelper::merge($this->parent->getReferenceCode(), $codes);
        }

		return $codes;
	}

	/**
	 * function getIsNewFile
	 */
	public function getIsNewFile(): bool
	{
        if ($this->archive_file != '' && $this->archive_file == $this->_file_preview_path) {
            return false;
        }
        if ($this->archive_file != '' && $this->_file_preview_path == null && preg_match("/^(archive)/", $this->archive_file)) {
            return false;
        }
		
		return true;
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

        if ($value !== null) {
            return $items[$value];
        } else {
            return $items;
        }
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

        if ($value !== null) {
            if ($value == '-') {
                return $value;
            }
			return $items[$value];
		} else {
            return $items;
        }
	}

	/**
	 * function parseParent
	 */
	public static function parseParent($model, $aciTree=true)
	{
        if (!isset($model)) {
            return Yii::$app->request->isAjax ? '-' : '<div id="tree" class="aciTree"></div>';
        }

		$title = self::htmlHardDecode($model->title);
		$levelName = $model->levelTitle->message;

		$items[] = $model->getAttributeLabel('level_id').': '.Html::a($levelName, ['/archive/setting/level/view', 'id' => $model->level_id], ['title' => $levelName, 'class' => 'modal-btn']);
		$items[] = Yii::t('app', '{level} Code: {code}', ['level' => $levelName, 'code' => $model->code]);
		$items[] = $model->getAttributeLabel('title').': '.Html::a($title, ['/archive/admin/view', 'id' => $model->id], ['title' => $title, 'class' => 'modal-btn']);

        if (Yii::$app->request->isAjax) {
            return Html::ul($items, ['encode' => false, 'class' => 'list-boxed']);
        }
		
		$return = Html::ul($items, ['encode' => false, 'class' => 'list-boxed']);
        if ($aciTree) {
            $return .= '<hr/><div id="tree" class="aciTree"></div>';
        }
		return $return;
	}

	/**
	 * function parseRelated
	 */
	public static function parseRelated($medias, $controller='media', $sep='li')
	{
        if (!is_array($medias) || (is_array($medias) && empty($medias))) {
            return '-';
        }

		$items = self::getRelatedUrl($medias, $controller, $controller != null ? true : false);

        if ($sep == 'li') {
			return Html::ul($items, ['item' => function($item, $index) {
				return Html::tag('li', $item);
			}, 'class' => 'list-boxed']);
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
            $items[$val] = $hyperlink ? Html::a($val, ['setting/'.$controller.'/view', 'id' => $key], ['title' => $val, 'class' => 'modal-btn']) : $val;
        }

		return $items;
	}

	/**
	 * function parseFilter
	 */
	public static function parseFilter($subjects, $attr='subjectId', $sep='li')
	{
        if (!is_array($subjects) || (is_array($subjects) && empty($subjects))) {
            return '-';
        }

		$items = [];
		foreach ($subjects as $key => $val) {
			$items[$val] = Html::a($val, ['admin/manage', $attr => $key], ['title' => $val]);
		}

        if ($sep == 'li') {
			return Html::ul($items, ['item' => function($item, $index) {
				return Html::tag('li', $item);
			}, 'class' => 'list-boxed']);
		}

		return implode($sep, $items);
	}

	/**
	 * function parseChilds
	 */
	public static function parseChilds($childs, $id=null, $sep='li')
	{
        if (empty($childs)) {
            return '-';
        }

		$levels = ArchiveLevel::getLevel();
		$return = [];
		$i = 0;
		foreach ($childs as $key => $val) {
			$i++;
			$title = $val." ".$levels[$key];
			$return[] = $i == 1 ? ($id != null ? Html::a($title, ['admin/manage', 'parent' => $id], ['title' => $title, 'data-pjax' => 0]) : $title) : $title;
		}

        if ($sep == 'li') {
            return Html::ul($return, ['encode' => false, 'class' => 'list-boxed']);
        }

		return implode(', ', $return);
	}

	/**
	 * function parseLocation
	 */
	public static function parseLocation($model)
	{
        if ($model == null) {
            return '-';
        }

        if (isset($model->rack)) {
            $items[] = Yii::t('app', 'Rack: {rack}', ['rack' => $model->rack->location_name]);
        }
        if (isset($model->room)) {
            $items[] = Yii::t('app', 'Location: {room}, {depo}, {building}', ['room' => $model->room->location_name, 'depo' => $model->depo->location_name, 'building' => $model->building->location_name]);
        }
        if (isset($model->storage)) {
            $items[] = Yii::t('app', 'Storage: {storage-name}', ['storage-name' => $model->storage->storage_name_i]);
        }
        if ($model->weight != '') {
            $items[] = Yii::t('app', 'Weight: {weight}', ['weight' => $model->weight]);
        }
        if ($model->location_desc != '') {
            $items[] = Yii::t('app', 'Noted: {location-desc}', ['location-desc' => $model->location_desc]);
        }

		return Html::ul($items, ['encode' => false, 'class' => 'list-boxed']);
	}

	/**
	 * function getLongCode
	 * @param short true|false 
	 * @param link true|false 
	 */
	public static function parseCode($model, $param=[])
	{
		$setting = ArchiveSetting::find()
			->select(['short_code', 'reference_code_sikn', 'reference_code_separator', 'maintenance_mode'])
			->where(['id' => 1])
			->one();

		$short_code = $setting->short_code;
        if (isset($param['short'])) {
            $short_code = $param['short'] ? 1 : 0;
        }

		$reference_code_separator = ' '.$setting->reference_code_separator.' ';
		$title = $model::htmlHardDecode($model->title);

        if ($short_code) {
            return join(' ', [$setting->reference_code_sikn, !$setting->maintenance_mode ? $model->code : $model->confirmCode]);
        }

        if (isset($param['link']) && $param['link']) {
			$count = count($model->referenceCode);
			$i = 0;
			$coder = [];
			foreach ($model->referenceCode as $key => $val) {
				$i++;
				$code = $setting->maintenance_mode ? $val['confirmCode'] : $val['code'];
                if ($i == $count) {
                    $coder[] = '<span class="badge badge-success">'.$code.'</span>';
                } else {
                    $coder[] = Html::a($code, ['/archive/site/view', 'id' => $val['id'], 't' => Inflector::slug($title)], ['title' => $title, 'class' => 'text-dark-gray']);
                }
			}
			return join(' ', [$setting->reference_code_sikn, join($reference_code_separator, $coder)]);
		}

        if ($setting->maintenance_mode) {
            return join(' ', [$setting->reference_code_sikn, join($reference_code_separator, ArrayHelper::map($model->referenceCode, 'level', 'confirmCode'))]);
        }

		return join(' ', [$setting->reference_code_sikn, join($reference_code_separator, ArrayHelper::map($model->referenceCode, 'level', 'code'))]);
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		$setting = $this->getSetting(['maintenance_mode']);

		parent::afterFind();

		$this->old_archive_file = $this->archive_file;
        $this->isFond = $this->level_id == 1 ? true : false;
		$this->preview = $this->archive_file != '' ? true : false;
		// $this->parentTitle = isset($this->parent) ? $this->parent->title : '-';
		// $this->levelName = isset($this->level) ? $this->level->title->message : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';

		// $this->media = array_flip($this->getMedias(true));
		// $this->creator = implode(',', $this->getCreators(true, 'title'));
		// $this->repository =  array_flip($this->getRepositories(true));
		// $this->subject =  implode(',', $this->getSubjects(true, 'title'));
		// $this->function =  implode(',', $this->getFunctions(true, 'title'));
		// $this->location = $this->getLocations(false) != null ? 1 : 0;

		$this->code = trim(preg_replace("/^[.-]/", "", preg_replace("/^(3400|23400-24)/", "", preg_replace("/[\s]/", "", $this->code))));
		$this->oldCode = $this->code;
        if ($this->parent) {
            $parentCode = $this->parent->code;
            if ($setting->maintenance_mode) {
                if ($this->parent->code == $this->parent->confirmCode) {
                    $parentCode = preg_replace("/[.-]$/", '', join('.', $this->getReferenceCode(true)));
                }
                $confirmCode = preg_replace("/^[.-]/", '', preg_replace("/^($parentCode)/", '', $this->code));
                $parentConfirmCode = $this->parent->confirmCode;
                if (preg_match("/^($parentConfirmCode)/", $confirmCode)) {
                    $shortCodeStatus = false;
                    $this->confirmCode = $confirmCode;
                } else {
                    $shortCodeStatus = true;
                    if (count(explode('.', $confirmCode)) == 1) {
                        $this->confirmCode = join('.', [$parentConfirmCode, $confirmCode]);
                    } else {
                        $this->confirmCode = $confirmCode;
                    }
                }
                $this->shortCode = $shortCodeStatus ? $confirmCode : preg_replace("/^[.-]/", '', preg_replace("/^($parentConfirmCode)/", '', $this->confirmCode));
            } else {
                $this->shortCode = preg_replace("/^[.-]/", '', preg_replace("/^($parentCode)/", '', $this->code));
            }
        } else {
            $this->confirmCode = $this->code;
            $this->shortCode = $this->code;
        }

		$this->oldConfirmCode = $this->confirmCode;
		$this->oldShortCode = $this->shortCode;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		$setting = $this->getSetting(['image_type', 'document_type']);

        if (parent::beforeValidate()) {
			// $this->archive_file = UploadedFile::getInstance($this, 'archive_file');
            if ($this->archive_file instanceof UploadedFile && !$this->archive_file->getHasError()) {
				$imageFileType = $this->formatFileType($setting->image_type);
				$documentFileType = $this->formatFileType($setting->document_type);
				$fileType = ArrayHelper::merge($imageFileType, $documentFileType);

                if (!in_array(strtolower($this->archive_file->getExtension()), $fileType)) {
					$this->addError('archive_file', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {image-extensions} for photo and {document-extensions} for document', [
						'name' => $this->archive_file->name,
						'image-extensions' => $setting->image_type,
						'document-extensions' => $setting->document_type,
					]));
				}
			}

            if ($this->isNewRecord) {
                if ($this->creation_id == null) {
                    $this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            } else {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		$setting = $this->getSetting(['maintenance_mode']);

		parent::beforeSave($insert);

		// set code
        if ($this->updateCode == true) {
            if (strtolower($this->levelTitle->message) == 'fond') {
                $this->code = $this->shortCode;
            } else {
                if ($setting->maintenance_mode) {
                    if (count(explode('.', $this->shortCode)) == 1) {
                        $this->code = join('.', [$this->parent->confirmCode, $this->shortCode]);
                    } else {
                        $this->code = $this->shortCode;
                    }
				} else {
                    $this->code = join('.', [$this->parent->code, $this->shortCode]);
                }
			}
			// $this->code = strtolower($this->levelTitle->message) == 'fond' ? 
			// 	$this->shortCode : 
			// 	($setting->maintenance_mode ? 
			// 		join('.', [$this->parent->confirmCode, $this->shortCode]) :
			// 		join('.', [$this->parent->code, $this->shortCode]));
		}
        $this->code = trim($this->code);
	
		// replace code
        if (!$insert && (array_key_exists('code', $this->dirtyAttributes) && $this->dirtyAttributes['code'] != $this->oldCode) && $this->getArchives('count') != 0) {
			$models = self::find()
                ->select(['id', 'publish', 'sidkkas', 'parent_id', 'level_id', 'code', 'archive_file'])
				->where(['parent_id' => $this->id])
				->all();
            if (!empty($models)) {
				foreach ($models as $model) {
                    if ($setting->maintenance_mode) {
                        $model->parent->confirmCode = $this->dirtyAttributes['code'];
                    } else {
                        $model->parent->code = $this->dirtyAttributes['code'];
                    }
					$model->updateCode = true;
					$model->update(false);
				}
			}
		}
		
        if (!$insert) {
			// set archive media, creator repository, subject and function
			$event = new Event(['sender' => $this]);
			Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_ARCHIVES, $event);

            $uploadPath = join('/', [self::getUploadPath(), $this->id]);
            $verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
            $this->createUploadDirectory(self::getUploadPath(), $this->id);

			// $this->archive_file = UploadedFile::getInstance($this, 'archive_file');
            if ($this->archive_file instanceof UploadedFile && !$this->archive_file->getHasError()) {
				$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->archive_file->getExtension());
                if ($this->archive_file->saveAs(join('/', [$uploadPath, $fileName]))) {
                    if ($this->old_archive_file != '' && file_exists(join('/', [$uploadPath, $this->old_archive_file]))) {
                        rename(join('/', [$uploadPath, $this->old_archive_file]), join('/', [$verwijderenPath.'-'.time().'_change_'.$this->old_archive_file]));
                    }
					$this->archive_file = $fileName;
                }
            } else {
                if ($this->archive_file == '') {
                    $this->archive_file = $this->old_archive_file;
                }
            }
        }

		return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
			// set archive media, creator repository, subject and function
			$event = new Event(['sender' => $this]);
			Event::trigger(self::className(), self::EVENT_BEFORE_SAVE_ARCHIVES, $event);

            $uploadPath = join('/', [self::getUploadPath(), $this->id]);
            $verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
            $this->createUploadDirectory(self::getUploadPath(), $this->id);

			// $this->archive_file = UploadedFile::getInstance($this, 'archive_file');
            if ($this->archive_file instanceof UploadedFile && !$this->archive_file->getHasError()) {
				$fileName = join('-', [time(), UuidHelper::uuid()]).'.'.strtolower($this->archive_file->getExtension());
                if ($this->archive_file->saveAs(join('/', [$uploadPath, $fileName]))) {
					self::updateAll(['archive_file' => $fileName], ['id' => $this->id]);
				}
			}

		} else {
			// update sidkkas status
            if (array_key_exists('sidkkas', $changedAttributes) && $changedAttributes['sidkkas'] != $this->sidkkas) {
				$models = self::find()
					->select(['id', 'publish', 'sidkkas', 'parent_id', 'level_id', 'code', 'archive_file'])
					->where(['parent_id' => $this->id])
					->all();
                if (!empty($models)) {
					foreach ($models as $model) {
						$model->updateCode = false;
						$model->sidkkas = $this->sidkkas;
						$model->update(false);
					}
				}
			}

			// delete and update archive childs publish condition
            if (array_key_exists('publish', $changedAttributes) && $changedAttributes['publish'] != $this->publish) {
				$models = self::find()
					->select(['id', 'publish', 'sidkkas', 'parent_id', 'level_id', 'code', 'archive_file'])
					->where(['parent_id' => $this->id])
					->all();
                if (!empty($models)) {
					foreach ($models as $model) {
                        if ($model->publish == 2) {
                            continue;
                        }
						$model->updateCode = false;
						$model->publish = $this->publish;
						$model->update(false);
					}
				}
			}
		}
	}

	/**
	 * After delete attributes
	 */
	public function afterDelete()
	{
        parent::afterDelete();

        $uploadPath = join('/', [self::getUploadPath(), $this->id]);
        $verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);

        if ($this->archive_file != '' && file_exists(join('/', [$uploadPath, $this->archive_file]))) {
            rename(join('/', [$uploadPath, $this->archive_file]), join('/', [$verwijderenPath, $this->id.'-'.time().'_deleted_'.$this->archive_file]));
        }
	}
}
