<?php
/**
 * ArchiveLevelGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 25 September 2022, 20:25 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 * This is the model class for table "ommu_archive_level_grid".
 *
 * The followings are the available columns in table "ommu_archive_level_grid":
 * @property integer $id
 * @property integer $archive
 * @property string $modified_date
 *
 * The followings are the available model relations:
 * @property ArchiveLevel $0
 *
 */

namespace ommu\archive\models;

use Yii;

class ArchiveLevelGrid extends \app\components\ActiveRecord
{
    public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_level_grid';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['id', 'archive'], 'required'],
			[['id', 'archive'], 'integer'],
			[['id'], 'unique'],
			[['id'], 'exist', 'skipOnError' => true, 'targetClass' => ArchiveLevel::className(), 'targetAttribute' => ['id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'archive' => Yii::t('app', 'Archive'),
			'modified_date' => Yii::t('app', 'Modified Date'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function get0()
	{
		return $this->hasOne(ArchiveLevel::className(), ['id' => 'id']);
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
	 * function getGrid
	 */
	public static function getGrid($array=true) 
	{
		$model = self::find()->alias('t')
			->select(['t.id', 't.id']);
		$model = $model->orderBy('t.id ASC')->all();

        if ($array == true) {
            return \yii\helpers\ArrayHelper::map($model, 'id', 'id');
        }

		return $model;
	}
}
