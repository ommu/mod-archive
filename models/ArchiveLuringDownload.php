<?php
/**
 * ArchiveLuringDownload
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 October 2022, 20:27 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 * This is the model class for table "ommu_archive_luring_download".
 *
 * The followings are the available columns in table "ommu_archive_luring_download":
 * @property string $id
 * @property integer $luring_id
 * @property integer $user_id
 * @property string $download_ip
 * @property string $download_date
 *
 * The followings are the available model relations:
 * @property ArchiveLurings $luring
 * @property Users $user
 *
 */

namespace ommu\archive\models;

use Yii;
use thamtech\uuid\helpers\UuidHelper;
use app\models\Users;

class ArchiveLuringDownload extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = [];

	public $archiveTitle;
	public $userDisplayname;
	public $archiveId;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_luring_download';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'luring_id', 'user_id', 'download_ip'], 'required'],
			[['luring_id', 'user_id'], 'integer'],
			[['id'], 'string', 'max' => 32],
			[['download_ip'], 'string', 'max' => 20],
			[['id'], 'unique'],
			[['luring_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchiveLurings::className(), 'targetAttribute' => ['luring_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'luring_id' => Yii::t('app', 'Luring'),
			'user_id' => Yii::t('app', 'User'),
			'download_ip' => Yii::t('app', 'Download Ip'),
			'download_date' => Yii::t('app', 'Download Date'),
			'archiveTitle' => Yii::t('app', 'Senarai'),
			'userDisplayname' => Yii::t('app', 'User'),
			'archiveId' => Yii::t('app', 'Senarai'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLuring()
	{
		return $this->hasOne(ArchiveLurings::className(), ['id' => 'luring_id'])
            ->select(['id', 'archive_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArchive()
	{
		return $this->hasOne(Archives::className(), ['id' => 'archive_id'])
            ->select(['id', 'level_id', 'title', 'code'])
            ->via('luring');
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
	 * @return \ommu\archive\models\query\ArchiveLuringDownload the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archive\models\query\ArchiveLuringDownload(get_called_class());
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
				return $model->luring::parseArchive($model->luring, false);
			},
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('luring') && !Yii::$app->request->get('archive') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
				// return $model->userDisplayname;
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['download_ip'] = [
			'attribute' => 'download_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->download_ip;
			},
		];
		$this->templateColumns['download_date'] = [
			'attribute' => 'download_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->download_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'download_date'),
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

		// $this->archiveTitle = isset($this->luring) ? $this->luring->archive->title : '-';
		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                $this->id = UuidHelper::uuid();

                if ($this->user_id == null) {
                    $this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
            $this->download_ip = $_SERVER['REMOTE_ADDR'];
        }
        return true;
	}
}
