<?php
/**
 * ArchiveLurings
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 October 2022, 20:24 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 * This is the model class for table "ommu_archive_lurings".
 *
 * The followings are the available columns in table "ommu_archive_lurings":
 * @property integer $id
 * @property integer $publish
 * @property integer $archive_id
 * @property string $introduction
 * @property string $senarai_file
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArchiveLuringDownload[] $downloads
 * @property ArchiveLuringGrid $grid
 * @property Archives $archive
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\archive\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;
use thamtech\uuid\helpers\UuidHelper;
use app\models\Users;

class ArchiveLurings extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

    public $gridForbiddenColumn = ['introduction', 'modified_date', 'updated_date', 'creationDisplayname', 'modifiedDisplayname'];

	public $old_senarai_file;
	public $archiveTitle;
	public $creationDisplayname;
	public $modifiedDisplayname;
	public $oDownload;
	public $oIntro;
	public $oFile;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_lurings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['archive_id', 'introduction'], 'required'],
			[['publish', 'archive_id', 'creation_id', 'modified_id'], 'integer'],
			[['introduction'], 'string'],
			[['senarai_file'], 'safe'],
			[['archive_id'], 'exist', 'skipOnError' => true, 'targetClass' => Archives::className(), 'targetAttribute' => ['archive_id' => 'id']],
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
			'archive_id' => Yii::t('app', 'Senarai'),
			'introduction' => Yii::t('app', 'Introduction'),
			'senarai_file' => Yii::t('app', 'Senarai File'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'old_senarai_file' => Yii::t('app', 'Old Senarai File'),
			'archiveTitle' => Yii::t('app', 'Senarai'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'oDownload' => Yii::t('app', 'Downloads'),
			'oIntro' => Yii::t('app', 'Introduction'),
			'oFile' => Yii::t('app', 'Senarai File'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDownloads($count=false)
	{
        if ($count == false) {
            return $this->hasMany(ArchiveLuringDownload::className(), ['luring_id' => 'id']);
        }

		$model = ArchiveLuringDownload::find()
            ->alias('t')
            ->where(['t.luring_id' => $this->id]);
		$downloads = $model->count();

		return $downloads ? $downloads : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getGrid()
	{
		return $this->hasOne(ArchiveLuringGrid::className(), ['id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArchive()
	{
		return $this->hasOne(Archives::className(), ['id' => 'archive_id'])
            ->select(['id', 'level_id', 'title', 'code']);
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
	 * {@inheritdoc}
	 * @return \ommu\archive\models\query\ArchiveLurings the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archive\models\query\ArchiveLurings(get_called_class());
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
		$this->templateColumns['archiveTitle'] = [
			'attribute' => 'archiveTitle',
			'value' => function($model, $key, $index, $column) {
				return $model::parseArchive($model, false);
			},
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('archive') ? true : false,
		];
		$this->templateColumns['introduction'] = [
			'attribute' => 'introduction',
			'value' => function($model, $key, $index, $column) {
				return $model->introduction;
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
		$this->templateColumns['oDownload'] = [
			'attribute' => 'oDownload',
			'value' => function($model, $key, $index, $column) {
				// $downloads = $model->getDownloads(true);
				$downloads = $model->grid->download;
				return Html::a($downloads, ['luring/download/manage', 'luring' => $model->primaryKey], ['title' => Yii::t('app', '{count} downloads', ['count' => $downloads]), 'data-pjax' => 0]);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['oIntro'] = [
			'attribute' => 'oIntro',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->oIntro);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['oFile'] = [
			'attribute' => 'oFile',
			'value' => function($model, $key, $index, $column) {
				return $this->filterYesNo($model->oFile);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id' => $model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
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
	 * @param returnAlias set true jika ingin kembaliannya path alias atau false jika ingin string
	 * relative path. default true.
	 */
	public static function getUploadPath($returnAlias=true) 
	{
		return ($returnAlias ? Yii::getAlias('@public/archive/senarai_luring') : 'archive/senarai_luring');
	}

	/**
	 * function parseArchive
	 */
	public static function parseArchive($model, $urlTitle=true)
	{
		$title = self::htmlHardDecode($model->archive->title);
        $archiveTitle = $urlTitle == true ? Html::a($title, ['admin/view', 'id' => $model->archive_id], ['title' => $title, 'class' => 'modal-btn']) : $title ;

        $html = $archiveTitle;
        if ($urlTitle == true) {
            $html .= '<hr class="mt-5 mb-5"/>';
        }

		$items[] = Html::tag('span', Yii::t('app', 'Code'), ['class' => 'btn btn-primary btn-xs']).$model->archive->code;
        if ($model->senarai_file != '') {
            $uploadPath = $model::getUploadPath(false);
            $items[] = Html::tag('span', Yii::t('app', 'Senarai File'), ['class' => 'btn btn-success btn-xs']).Html::a($model->senarai_file, Url::to(join('/', ['@webpublic', $uploadPath, $model->senarai_file])), ['title' => $model->senarai_file, 'data-pjax' => 0, 'target' => '_blank']);
        }
		
		$html .= Html::ul($items, ['encode' => false, 'class' => 'list-boxed mt-5']);

		return $html;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->old_senarai_file = $this->senarai_file;
		// $this->archiveTitle = isset($this->archive) ? $this->archive->title : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		// $this->download = $this->getDownloads(true) ? 1 : 0;
		// $this->oDownload = isset($this->grid) ? $this->grid->download : 0;
		$this->oIntro = $this->introduction != '' ? true : false;
		$this->oFile = $this->senarai_file != '' ? true : false;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            // $this->senarai_file = UploadedFile::getInstance($this, 'senarai_file');
            if ($this->senarai_file instanceof UploadedFile && !$this->senarai_file->getHasError()) {
                $senaraiFileFileType = ['pdf'];
                if (!in_array(strtolower($this->senarai_file->getExtension()), $senaraiFileFileType)) {
                    $this->addError('senarai_file', Yii::t('app', 'The file {name} cannot be uploaded. Only files with these extensions are allowed: {extensions}', [
                        'name' => $this->senarai_file->name,
                        'extensions' => $this->formatFileType($senaraiFileFileType, false),
                    ]));
                }
            } /* else {
                if ($this->isNewRecord || (!$this->isNewRecord && $this->old_senarai_file == '')) {
                    $this->addError('senarai_file', Yii::t('app', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('senarai_file')]));
                }
            } */

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
        if (parent::beforeSave($insert)) {
            if (!$insert) {
                $uploadPath = self::getUploadPath();
                $verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
                $this->createUploadDirectory(self::getUploadPath());

                // $this->senarai_file = UploadedFile::getInstance($this, 'senarai_file');
                if ($this->senarai_file instanceof UploadedFile && !$this->senarai_file->getHasError()) {
                    $fileName = join('_', [$this->archive->code, UuidHelper::uuid()]).'.'.strtolower($this->senarai_file->getExtension()); 
                    if ($this->senarai_file->saveAs(join('/', [$uploadPath, $fileName]))) {
                        if ($this->old_senarai_file != '' && file_exists(join('/', [$uploadPath, $this->old_senarai_file]))) {
                            rename(join('/', [$uploadPath, $this->old_senarai_file]), join('/', [$verwijderenPath, $this->id.'-'.time().'_change_'.$this->old_senarai_file]));
                        }
                        $this->senarai_file = $fileName;
                    }
                } else {
                    if ($this->senarai_file == '') {
                        $this->senarai_file = $this->old_senarai_file;
                    }
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

        $uploadPath = self::getUploadPath();
        $verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);
        $this->createUploadDirectory(self::getUploadPath());

        if ($insert) {
            // $this->senarai_file = UploadedFile::getInstance($this, 'senarai_file');
            if ($this->senarai_file instanceof UploadedFile && !$this->senarai_file->getHasError()) {
                $fileName = join('_', [$this->archive->code, UuidHelper::uuid()]).'.'.strtolower($this->senarai_file->getExtension()); 
                if ($this->senarai_file->saveAs(join('/', [$uploadPath, $fileName]))) {
                    self::updateAll(['senarai_file' => $fileName], ['id' => $this->id]);
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

		$uploadPath = self::getUploadPath();
		$verwijderenPath = join('/', [self::getUploadPath(), 'verwijderen']);

        if ($this->senarai_file != '' && file_exists(join('/', [$uploadPath, $this->senarai_file]))) {
            rename(join('/', [$uploadPath, $this->senarai_file]), join('/', [$verwijderenPath, time().'_deleted_'.$this->senarai_file]));
        }
	}
}
