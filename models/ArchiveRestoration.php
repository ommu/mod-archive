<?php
/**
 * ArchiveRestoration
 * 
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 01:11 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 * This is the model class for table "ommu_archive_restoration".
 *
 * The followings are the available columns in table "ommu_archive_restoration":
 * @property string $id
 * @property integer $archive_id
 * @property string $condition
 * @property string $condition_date
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property Archives $archive
 * @property ArchiveRestorationHistory[] $histories
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\archive\models;

use Yii;
use yii\helpers\Html;
use thamtech\uuid\helpers\UuidHelper;
use app\models\Users;

class ArchiveRestoration extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = ['creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname'];

    public $stayInHere;

	public $archiveTitle;
	public $archiveCode;
	public $creationDisplayname;
	public $modifiedDisplayname;

    public $oHistory;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_restoration';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'archive_id'], 'required'],
			[['archive_id', 'creation_id', 'modified_id', 'stayInHere'], 'integer'],
			[['condition'], 'string'],
			[['condition_date', 'stayInHere'], 'safe'],
			[['id'], 'string', 'max' => 36],
			[['id'], 'unique'],
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
			'archive_id' => Yii::t('app', 'Archive'),
			'condition' => Yii::t('app', 'Restoration Status'),
			'condition_date' => Yii::t('app', 'Restoration Date'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'stayInHere' => Yii::t('app', 'stayInHere'),
			'archiveTitle' => Yii::t('app', 'Archive'),
			'archiveCode' => Yii::t('app', 'Reference code'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
			'oHistory' => Yii::t('app', 'History'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArchive()
	{
		return $this->hasOne(Archives::className(), ['id' => 'archive_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories($count=false)
	{
        if ($count == false) {
            return $this->hasMany(ArchiveRestorationHistory::className(), ['restoration_id' => 'id']);
        }

		$model = ArchiveRestorationHistory::find()
            ->alias('t')
            ->where(['t.restoration_id' => $this->id]);
		$histories = $model->count();

		return $histories ? $histories : 0;
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
	 * @return \ommu\archive\models\query\ArchiveRestoration the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archive\models\query\ArchiveRestoration(get_called_class());
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
				return isset($model->archive) ? $model->archive->title : '-';
				// return $model->archiveTitle;
			},
			'format' => 'html',
			'visible' => !Yii::$app->request->get('archive') ? true : false,
		];
		$this->templateColumns['archiveCode'] = [
			'attribute' => 'archiveCode',
			'value' => function($model, $key, $index, $column) {
				return isset($model->archive) ? $model->archive->code : '-';
				// return $model->archiveCode;
			},
			'visible' => !Yii::$app->request->get('archive') ? true : false,
		];
		$this->templateColumns['condition'] = [
			'attribute' => 'condition',
			'value' => function($model, $key, $index, $column) {
				return self::getCondition($model->condition);
			},
			'filter' => self::getCondition(),
		];
		$this->templateColumns['condition_date'] = [
			'attribute' => 'condition_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->condition_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'condition_date'),
		];
		$this->templateColumns['oHistory'] = [
			'attribute' => 'oHistory',
			'value' => function($model, $key, $index, $column) {
				$history = $model->getHistories(true);
				return Html::a($history, ['restoration/history/manage', 'restoration' => $model->primaryKey], ['title' => Yii::t('app', '{count} histories', ['count' => $history]), 'data-pjax' => 0]);
			},
			'contentOptions' => ['class' => 'text-center'],
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
	 * function getCondition
	 */
	public static function getCondition($value=null)
	{
		$items = array(
			'open' => Yii::t('app', 'Open'),
			'process' => Yii::t('app', 'Process'),
			'close' => Yii::t('app', 'Close'),
		);

        if ($value !== null) {
            return $items[$value];
        } else {
            return $items;
        }
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->archiveTitle = isset($this->archive) ? $this->archive->title : '-';
		// $this->archiveCode = isset($this->archive) ? $this->archive->code : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
		// $this->history = $this->getHistories(true) ? 1 : 0;
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            $this->id = UuidHelper::uuid();
        
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
}
