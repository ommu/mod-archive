<?php
/**
 * ArchiveViews
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 12 Fabruary 2020, 08:42 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 * This is the model class for table "ommu_archive_views".
 *
 * The followings are the available columns in table "ommu_archive_views":
 * @property integer $id
 * @property integer $publish
 * @property integer $archive_id
 * @property integer $user_id
 * @property integer $views
 * @property string $view_date
 * @property string $view_ip
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArchiveViewHistory[] $histories
 * @property Archives $archive
 * @property Users $user
 *
 */

namespace ommu\archive\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;

class ArchiveViews extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['view_ip', 'updated_date'];

	public $archiveTitle;
	public $userDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_views';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['archive_id'], 'required'],
			[['publish', 'archive_id', 'user_id', 'views'], 'integer'],
			[['user_id', 'view_ip'], 'safe'],
			[['view_ip'], 'string', 'max' => 20],
			[['archive_id'], 'exist', 'skipOnError' => true, 'targetClass' => Archives::className(), 'targetAttribute' => ['archive_id' => 'id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
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
			'archive_id' => Yii::t('app', 'Archive'),
			'user_id' => Yii::t('app', 'User'),
			'views' => Yii::t('app', 'Views'),
			'view_date' => Yii::t('app', 'View Date'),
			'view_ip' => Yii::t('app', 'View IP'),
			'updated_date' => Yii::t('app', 'Deleted Date'),
			'histories' => Yii::t('app', 'Histories'),
			'archiveTitle' => Yii::t('app', 'Archive'),
			'userDisplayname' => Yii::t('app', 'User'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories($count=false)
	{
        if ($count == false) {
            return $this->hasMany(ArchiveViewHistory::className(), ['view_id' => 'id']);
        }

		$model = ArchiveViewHistory::find()
            ->alias('t')
            ->where(['t.view_id' => $this->id]);
		$histories = $model->count();

		return $histories ? $histories : 0;
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
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\query\ArchiveViews the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archive\models\query\ArchiveViews(get_called_class());
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
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['views'] = [
			'attribute' => 'views',
			'value' => function($model, $key, $index, $column) {
				$views = $model->views;
				return Html::a($views, ['view/history/manage', 'view' => $model->primaryKey], ['title' => Yii::t('app', '{count} histories', ['count' => $histories]), 'data-pjax' => 0]);
			},
			'filter' => false,
			'contentOptions' => ['class' => 'text-center'],
			'format' => 'raw',
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
	 * function insertView
	 */
	public function insertView($archive_id, $user_id=null)
	{
        if ($user_id == null) {
            $user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
        }

		$findView = self::find()
			->select(['id', 'views'])
			->where(['publish' => 1])
			->andWhere(['archive_id' => $archive_id]);
        if ($user_id != null) {
            $findView->andWhere(['user_id' => $user_id]);
        } else {
            $findView->andWhere(['is', 'user_id', null]);
        }
		$findView = $findView->one();

        if ($findView !== null) {
            $findView->updateAttributes(['views' => $findView->views+1, 'view_ip' => $_SERVER['REMOTE_ADDR']]);
        } else {
			$view = new ArchiveViews();
			$view->archive_id = $archive_id;
			$view->user_id = $user_id;
			$view->save();
		}
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->archiveTitle = isset($this->archive) ? $this->archive->title : '-';
		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                if ($this->user_id == null) {
                    $this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
            $this->view_ip = $_SERVER['REMOTE_ADDR'];
        }
        return true;
	}
}
