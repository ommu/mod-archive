<?php
/**
 * ArchiveSetting
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 5 March 2019, 23:31 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 * This is the model class for table "ommu_archive_setting".
 *
 * The followings are the available columns in table "ommu_archive_setting":
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_description
 * @property string $meta_keyword
 * @property integer $fond_sidkkas
 * @property string $reference_code_sikn
 * @property string $reference_code_separator
 * @property integer $short_code
 * @property integer $medium_sublevel
 * @property string $production_date
 * @property string $image_type
 * @property string $document_type
 * @property integer $maintenance_mode
 * @property string $maintenance_image_path
 * @property string $maintenance_document_path
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property Users $modified
 *
 */

namespace ommu\archive\models;

use Yii;
use ommu\users\models\Users;
use yii\helpers\Json;

class ArchiveSetting extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;
	use \ommu\traits\FileTrait;

	public $gridForbiddenColumn = [];

	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['license', 'permission', 'meta_description', 'meta_keyword', 'fond_sidkkas', 'reference_code_separator', 'short_code', 'medium_sublevel', 'production_date', 'image_type', 'document_type', 'maintenance_mode'], 'required'],
			[['permission', 'fond_sidkkas', 'short_code', 'medium_sublevel', 'maintenance_mode', 'modified_id'], 'integer'],
			[['meta_description', 'meta_keyword', 'production_date', 'maintenance_image_path', 'maintenance_document_path'], 'string'],
			[['reference_code_sikn', 'maintenance_image_path', 'maintenance_document_path'], 'safe'],
			//[['image_type', 'document_type'], 'json'],
			[['license', 'reference_code_sikn', 'maintenance_image_path', 'maintenance_document_path'], 'string', 'max' => 32],
			[['reference_code_separator'], 'string', 'max' => 1],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'license' => Yii::t('app', 'License'),
			'permission' => Yii::t('app', 'Permission'),
			'meta_description' => Yii::t('app', 'Meta Description'),
			'meta_keyword' => Yii::t('app', 'Meta Keyword'),
			'fond_sidkkas' => Yii::t('app', 'Fond Sidkkas'),
			'reference_code_sikn' => Yii::t('app', 'SIKN Reference Code'),
			'reference_code_separator' => Yii::t('app', 'Reference Code Level Separator'),
			'short_code' => Yii::t('app', 'Short Code'),
			'medium_sublevel' => Yii::t('app', 'Extent and medium with sublevel'),
			'production_date' => Yii::t('app', 'Production Date'),
			'image_type' => Yii::t('app', 'Image Type'),
			'document_type' => Yii::t('app', 'Document Type'),
			'maintenance_mode' => Yii::t('app', 'Maintenance Mode'),
			'maintenance_image_path' => Yii::t('app', 'Maintenance Image Path'),
			'maintenance_document_path' => Yii::t('app', 'Maintenance Document Path'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		if(!$this->hasMethod('search'))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['license'] = [
			'attribute' => 'license',
			'value' => function($model, $key, $index, $column) {
				return $model->license;
			},
		];
		$this->templateColumns['permission'] = [
			'attribute' => 'permission',
			'value' => function($model, $key, $index, $column) {
				return self::getPermission($model->permission);
			},
		];
		$this->templateColumns['meta_description'] = [
			'attribute' => 'meta_description',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_description;
			},
		];
		$this->templateColumns['meta_keyword'] = [
			'attribute' => 'meta_keyword',
			'value' => function($model, $key, $index, $column) {
				return $model->meta_keyword;
			},
		];
		$this->templateColumns['reference_code_sikn'] = [
			'attribute' => 'reference_code_sikn',
			'value' => function($model, $key, $index, $column) {
				return $model->reference_code_sikn;
			},
		];
		$this->templateColumns['reference_code_separator'] = [
			'attribute' => 'reference_code_separator',
			'value' => function($model, $key, $index, $column) {
				return $model->reference_code_separator;
			},
		];
		$this->templateColumns['production_date'] = [
			'attribute' => 'production_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDate($model->production_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'production_date'),
		];
		$this->templateColumns['image_type'] = [
			'attribute' => 'image_type',
			'value' => function($model, $key, $index, $column) {
				return $model->image_type;
			},
		];
		$this->templateColumns['document_type'] = [
			'attribute' => 'document_type',
			'value' => function($model, $key, $index, $column) {
				return $model->document_type;
			},
		];
		$this->templateColumns['maintenance_image_path'] = [
			'attribute' => 'maintenance_image_path',
			'value' => function($model, $key, $index, $column) {
				return $model->maintenance_image_path;
			},
		];
		$this->templateColumns['maintenance_document_path'] = [
			'attribute' => 'maintenance_document_path',
			'value' => function($model, $key, $index, $column) {
				return $model->maintenance_document_path;
			},
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
		$this->templateColumns['fond_sidkkas'] = [
			'attribute' => 'fond_sidkkas',
			'value' => function($model, $key, $index, $column) {
				return self::getFondSidkkas($model->fond_sidkkas);
			},
			'filter' => self::getFondSidkkas(),
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['short_code'] = [
			'attribute' => 'short_code',
			'value' => function($model, $key, $index, $column) {
				return self::getFondSidkkas($model->short_code);
			},
			'filter' => self::getFondSidkkas(),
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['medium_sublevel'] = [
			'attribute' => 'medium_sublevel',
			'value' => function($model, $key, $index, $column) {
				return self::getFondSidkkas($model->medium_sublevel);
			},
			'filter' => self::getFondSidkkas(),
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['maintenance_mode'] = [
			'attribute' => 'maintenance_mode',
			'value' => function($model, $key, $index, $column) {
				return self::getFondSidkkas($model->maintenance_mode);
			},
			'filter' => self::getFondSidkkas(),
			'contentOptions' => ['class'=>'text-center'],
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['id' => 1])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne(1);
			return $model;
		}
	}

	/**
	 * function getPermission
	 */
	public static function getPermission($value=null)
	{
		$moduleName = "module name";
		$module = strtolower(Yii::$app->controller->module->id);
		if(($module = Yii::$app->moduleManager->getModule($module)) != null);
			$moduleName = strtolower($module->getName());

		$items = array(
			1 => Yii::t('app', 'Yes, the public can view {module} unless they are made private.', ['module'=>$moduleName]),
			0 => Yii::t('app', 'No, the public cannot view {module}.', ['module'=>$moduleName]),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * function getFondSidkkas
	 */
	public static function getFondSidkkas($value=null)
	{
		$items = array(
			'0' => Yii::t('app', 'Disable'),
			'1' => Yii::t('app', 'Enable'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$image_type = Json::decode($this->image_type);
		if(!empty($image_type))
			$this->image_type = $this->formatFileType($image_type, false);
		$document_type = Json::decode($this->document_type);
		if(!empty($document_type))
			$this->document_type = $this->formatFileType($document_type, false);
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if(!$this->isNewRecord) {
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
		if(parent::beforeSave($insert)) {
			$this->production_date = Yii::$app->formatter->asDate($this->production_date, 'php:Y-m-d');
			$this->image_type = Json::encode($this->formatFileType($this->image_type));
			$this->document_type = Json::encode($this->formatFileType($this->document_type));
		}
		return true;
	}
}
