<?php
/**
 * ArchiveRestorationHistory
 * 
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 01:12 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 * This is the model class for table "ommu_archive_restoration_history".
 *
 * The followings are the available columns in table "ommu_archive_restoration_history":
 * @property string $id
 * @property string $restoration_id
 * @property string $condition
 * @property string $condition_date
 * @property integer $creation_id
 *
 * The followings are the available model relations:
 * @property ArchiveRestoration $restoration
 * @property Users $creation
 *
 */

namespace ommu\archive\models;

use Yii;
use thamtech\uuid\helpers\UuidHelper;
use app\models\Users;

class ArchiveRestorationHistory extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = ['restorationArchiveId'];

    public $stayInHere;

	public $restorationArchiveId;
	public $creationDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_restoration_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['restoration_id'], 'required'],
			[['creation_id', 'stayInHere'], 'integer'],
			[['condition'], 'string'],
			[['condition_date', 'stayInHere'], 'safe'],
			[['id', 'restoration_id'], 'string', 'max' => 36],
			[['id'], 'unique'],
			[['restoration_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchiveRestoration::className(), 'targetAttribute' => ['restoration_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'restoration_id' => Yii::t('app', 'Restoration'),
			'condition' => Yii::t('app', 'Condition'),
			'condition_date' => Yii::t('app', 'Condition Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'stayInHere' => Yii::t('app', 'stayInHere'),
			'restorationArchiveId' => Yii::t('app', 'Restoration'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRestoration()
	{
		return $this->hasOne(ArchiveRestoration::className(), ['id' => 'restoration_id']);
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
	 * {@inheritdoc}
	 * @return \ommu\archive\models\query\ArchiveRestorationHistory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archive\models\query\ArchiveRestorationHistory(get_called_class());
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
		$this->templateColumns['restorationArchiveId'] = [
			'attribute' => 'restorationArchiveId',
			'value' => function($model, $key, $index, $column) {
				return isset($model->restoration) ? $model->restoration->archive->title : '-';
				// return $model->restorationArchiveId;
			},
			'visible' => !Yii::$app->request->get('restoration') ? true : false,
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
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
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

		// $this->restorationArchiveId = isset($this->restoration) ? $this->restoration->archive->title : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
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
            $this->condition_date = Yii::$app->formatter->asDate($this->condition_date, 'php:Y-m-d');
        }
        return true;
	}
}
