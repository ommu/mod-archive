<?php
/**
 * ArchiveFavourites
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 7 October 2022, 22:44 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 * This is the model class for table "ommu_archive_favourites".
 *
 * The followings are the available columns in table "ommu_archive_favourites":
 * @property integer $id
 * @property integer $publish
 * @property integer $archive_id
 * @property integer $user_id
 * @property string $creation_ip
 * @property string $creation_date
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property ArchiveFavouriteHistory[] $histories
 * @property Archives $archive
 * @property Users $user
 *
 */

namespace ommu\archive\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;

class ArchiveFavourites extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

    public $gridForbiddenColumn = ['updated_date'];

	public $archiveTitle;
	public $userDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_favourites';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['archive_id', 'user_id', 'creation_ip'], 'required'],
			[['publish', 'archive_id', 'user_id'], 'integer'],
			[['creation_ip'], 'string', 'max' => 20],
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
			'archive_id' => Yii::t('app', 'Archive'),
			'user_id' => Yii::t('app', 'User'),
			'creation_ip' => Yii::t('app', 'Creation Ip'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'updated_date' => Yii::t('app', 'Updated Date'),
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
            return $this->hasMany(ArchiveFavouriteHistory::className(), ['favourite_id' => 'id']);
        }

		$model = ArchiveFavouriteHistory::find()
            ->alias('t')
            ->where(['t.favourite_id' => $this->id]);
		$histories = $model->count();

		return $histories ? $histories : 0;
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
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id'])
            ->select(['user_id', 'displayname']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\query\ArchiveFavourites the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archive\models\query\ArchiveFavourites(get_called_class());
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
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
				// return $model->userDisplayname;
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['creation_ip'] = [
			'attribute' => 'creation_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->creation_ip;
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
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
	 * function parseArchive
	 */
	public static function parseArchive($model, $urlTitle=true)
	{
		$title = self::htmlHardDecode($model->archive->title);
        $archiveTitle = $urlTitle == true ? Html::a($title, ['admin/view', 'id' => $model->archive_id], ['title' => $title, 'class' => 'modal-btn']) : $title ;

        $html = Html::button($model->archive->code, ['class' => 'btn btn-info btn-xs']).'<br/>';
        $html .= $archiveTitle;

		return $html;
	}

	/**
	 * function insertFavourite
	 */

    public function insertFavourite($archive_id, $user_id=null)
    {
        if ($user_id == null) {
            $user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
        }

        $model = self::find()
            ->select(['id', 'publish'])
            ->andWhere(['archive_id' => $archive_id])
            ->andWhere(['user_id' => $user_id])
            ->one();

        if ($model !== null) {
            if ($model->publish == 0) {
                $model->publish = 1;
                $model->save();
            }
        } else {
            $model = new ArchiveFavourites();
            $model->archive_id = $archive_id;
            $model->user_id = $user_id;
            $model->save();
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
		// $this->history = $this->getHistories(true) ? 1 : 0;
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
            $this->creation_ip = $_SERVER['REMOTE_ADDR'];
        }
        return true;
	}
}
