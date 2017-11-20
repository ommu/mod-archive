<?php
/**
 * ArchiveSettings
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 13 June 2016, 23:51 WIB
 * @link https://github.com/ommu/mod-archive
 * @contact (+62)856-299-4114
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "ommu_archive_settings".
 *
 * The followings are the available columns in table 'ommu_archive_settings':
 * @property integer $id
 * @property string $license
 * @property integer $permission
 * @property string $meta_keyword
 * @property string $meta_description
 * @property string $auto_numbering
 * @property string $modified_date
 * @property string $modified_id
 */
class ArchiveSettings extends CActiveRecord
{
	public $defaultColumns = array();
	
	// Variable Search
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArchiveSettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ommu_archive_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('license, permission, meta_keyword, meta_description, auto_numbering', 'required'),
			array('permission, auto_numbering', 'numerical', 'integerOnly'=>true),
			array('license', 'length', 'max'=>32),
			array('modified_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, license, permission, meta_keyword, meta_description, auto_numbering, modified_date, modified_id,
				modified_search', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'license' => Yii::t('attribute', 'License'),
			'permission' => Yii::t('attribute', 'Permission'),
			'meta_keyword' => Yii::t('attribute', 'Meta Keyword'),
			'meta_description' => Yii::t('attribute', 'Meta Description'),
			'auto_numbering' => Yii::t('attribute', 'Auto Numbering'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'modified_search' => Yii::t('attribute', 'Modified'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		
		// Custom Search
		$criteria->with = array(
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname',
			),
		);

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.license',strtolower($this->license),true);
		$criteria->compare('t.permission',$this->permission);
		$criteria->compare('t.meta_keyword',strtolower($this->meta_keyword),true);
		$criteria->compare('t.meta_description',strtolower($this->meta_description),true);
		$criteria->compare('t.auto_numbering',$this->auto_numbering);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		if(isset($_GET['modified']))
			$criteria->compare('t.modified_id',$_GET['modified']);
		else
			$criteria->compare('t.modified_id',$this->modified_id);
		
		$criteria->compare('modified.displayname',strtolower($this->modified_search),true);

		if(!isset($_GET['ArchiveSettings_sort']))
			$criteria->order = 't.id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		} else {
			//$this->defaultColumns[] = 'id';
			$this->defaultColumns[] = 'license';
			$this->defaultColumns[] = 'permission';
			$this->defaultColumns[] = 'meta_keyword';
			$this->defaultColumns[] = 'meta_description';
			$this->defaultColumns[] = 'auto_numbering';
			$this->defaultColumns[] = 'modified_date';
			$this->defaultColumns[] = 'modified_id';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			/*
			$this->defaultColumns[] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			*/
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			$this->defaultColumns[] = 'license';
			$this->defaultColumns[] = 'permission';
			$this->defaultColumns[] = 'meta_keyword';
			$this->defaultColumns[] = 'meta_description';
			$this->defaultColumns[] = 'auto_numbering';
			$this->defaultColumns[] = array(
				'name' => 'modified_date',
				'value' => 'Utility::dateFormat($data->modified_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'modified_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'modified_date_filter',
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'dd-mm-yy',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
			);
			$this->defaultColumns[] = array(
				'name' => 'modified_search',
				'value' => '$data->modified->displayname',
			);
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk(1,array(
				'select' => $column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk(1);
			return $model;			
		}
	}

	/**
	 * get Module License
	 */
	public static function getLicense($source='1234567890', $length=16, $char=4)
	{
		$mod = $length%$char;
		if($mod == 0)
			$sep = ($length/$char);
		else
			$sep = (int)($length/$char)+1;
		
		$sourceLength = strlen($source);
		$random = '';
		for ($i = 0; $i < $length; $i++)
			$random .= $source[rand(0, $sourceLength - 1)];
		
		$license = '';
		for ($i = 0; $i < $sep; $i++) {
			if($i != $sep-1)
				$license .= substr($random,($i*$char),$char).'-';
			else
				$license .= substr($random,($i*$char),$char);
		}

		return $license;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {			
			$this->modified_id = Yii::app()->user->id;
		}
		return true;
	}

}