<?php
/**
 * ArchiveViewHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 12 Fabruary 2020, 08:42 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 * This is the model class for table "ommu_archive_view_history".
 *
 * The followings are the available columns in table "ommu_archive_view_history":
 * @property integer $id
 * @property integer $view_id
 * @property string $view_date
 * @property string $view_ip
 *
 * The followings are the available model relations:
 * @property ArchiveViews $view
 *
 */

namespace ommu\archive\models;

use Yii;
use app\models\Users;

class ArchiveViewHistory extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = [];

	public $counts;

	public $archiveTitle;
	public $userDisplayname;
	public $archiveCode;
	public $archiveId;
	public $levelId;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_view_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['view_id', 'view_ip'], 'required'],
			[['view_id'], 'integer'],
			[['view_date'], 'safe'],
			[['view_ip'], 'string', 'max' => 20],
			[['view_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchiveViews::className(), 'targetAttribute' => ['view_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'view_id' => Yii::t('app', 'View'),
			'view_date' => Yii::t('app', 'View Date'),
			'view_ip' => Yii::t('app', 'View IP'),
			'archiveTitle' => Yii::t('app', 'Archive'),
			'userDisplayname' => Yii::t('app', 'User'),
			'archiveCode' => Yii::t('app', 'Reference code'),
			'archiveId' => Yii::t('app', 'Archive'),
			'levelId' => Yii::t('app', 'Level of Description'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(ArchiveViews::className(), ['id' => 'view_id'])
            ->select(['id', 'archive_id', 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArchive()
	{
		return $this->hasOne(Archives::className(), ['id' => 'archive_id'])
            ->select(['id', 'parent_id', 'level_id', 'title', 'code', 'archive_date', 'archive_file', 'creation_date'])
            ->via('view');
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id'])
            ->select(['user_id', 'displayname'])
            ->via('view');
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\query\ArchiveViewHistory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archive\models\query\ArchiveViewHistory(get_called_class());
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
		$this->templateColumns['levelId'] = [
			'attribute' => 'levelId',
			'label' => Yii::t('app', 'Level'),
			'value' => function($model, $key, $index, $column) {
				return isset($model->archive->levelTitle) ? $model->archive->levelTitle->message : '-';
			},
			'filter' => ArchiveLevel::getLevel(),
			'visible' => !Yii::$app->request->get('view') && !Yii::$app->request->get('archive') && !Yii::$app->request->get('level') ? true : false,
		];
		$this->templateColumns['archiveCode'] = [
			'attribute' => 'archiveCode',
			'value' => function($model, $key, $index, $column) {
				return $model->archive->code;
			},
			'visible' => !Yii::$app->request->get('view') && !Yii::$app->request->get('archive') ? true : false,
		];
		$this->templateColumns['archiveTitle'] = [
			'attribute' => 'archiveTitle',
			'value' => function($model, $key, $index, $column) {
				return isset($model->archive) ? $model->archive->title : '-';
				// return $model->archiveTitle;
			},
            'format' => 'html',
			'visible' => !Yii::$app->request->get('view') && !Yii::$app->request->get('archive') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
				// return $model->userDisplayname;
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['view_date'] = [
			'attribute' => 'view_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->view_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'view_date'),
		];
		$this->templateColumns['view_ip'] = [
			'attribute' => 'view_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->view_ip;
			},
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
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->archiveTitle = isset($this->view) ? $this->view->archive->title : '-';
		// $this->userDisplayname = isset($this->view->user) ? $this->view->user->displayname : '-';
	}
}
