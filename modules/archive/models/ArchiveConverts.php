<?php
/**
 * ArchiveConverts
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 19 June 2016, 01:22 WIB
 * @link http://company.ommu.co
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
 * This is the model class for table "ommu_archive_converts".
 *
 * The followings are the available columns in table 'ommu_archive_converts':
 * @property string $convert_id
 * @property integer $publish
 * @property integer $location_id
 * @property integer $category_id
 * @property string $convert_parent
 * @property string $convert_title
 * @property string $convert_desc
 * @property integer $convert_cat_id
 * @property string $convert_numbers
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property OmmuArchiveConvertMedia[] $ommuArchiveConvertMedias
 * @property OmmuArchiveConvertCategory $category
 */
class ArchiveConverts extends CActiveRecord
{
	public $defaultColumns = array();
	public $convert_total;
	public $back_field;
	public $convert_number;
	
	// Variable Search
	public $code_search;
	public $creation_search;
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArchiveConverts the static model class
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
		return 'ommu_archive_converts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('location_id, category_id, convert_title', 'required'),
			array('convert_cat_id', 'required', 'on'=>'not_auto_numbering'),
			array('publish, location_id, category_id, convert_cat_id,
				back_field', 'numerical', 'integerOnly'=>true),
			array('convert_parent, creation_id, modified_id', 'length', 'max'=>11),
			array('convert_desc, convert_numbers,
				convert_number', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('convert_id, publish, location_id, category_id, convert_parent, convert_title, convert_desc, convert_cat_id, convert_numbers, creation_date, creation_id, modified_date, modified_id,
				convert_total, code_search, creation_search, modified_search', 'safe', 'on'=>'search'),
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
			'location' => array(self::BELONGS_TO, 'ArchiveLocation', 'location_id'),
			'category' => array(self::BELONGS_TO, 'ArchiveConvertCategory', 'category_id'),
			'creation_relation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified_relation' => array(self::BELONGS_TO, 'Users', 'modified_id'),
			'medias' => array(self::HAS_MANY, 'ArchiveConvertMedia', 'convert_id'),
			'view' => array(self::BELONGS_TO, 'ViewArchiveConverts', 'convert_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'convert_id' => Yii::t('attribute', 'Convert'),
			'publish' => Yii::t('attribute', 'Publish'),
			'location_id' => Yii::t('attribute', 'Location'),
			'category_id' => Yii::t('attribute', 'Category'),
			'convert_parent' => Yii::t('attribute', 'Parent'),
			'convert_title' => Yii::t('attribute', 'Title'),
			'convert_desc' => Yii::t('attribute', 'Description'),
			'convert_cat_id' => Yii::t('attribute', 'Convert Category ID'),
			'convert_numbers' => Yii::t('attribute', 'Numbers'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'convert_total' => Yii::t('attribute', 'Total'),
			'back_field' => Yii::t('attribute', 'Back to Manage'),
			'convert_number' => Yii::t('attribute', 'Numbers'),
			'code_search' => Yii::t('attribute', 'Code'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
		);
		/*
			'Convert' => 'Convert',
			'Publish' => 'Publish',
			'Category' => 'Category',
			'Convert Parent' => 'Convert Parent',
			'Convert Title' => 'Convert Title',
			'Convert Desc' => 'Convert Desc',
			'Creation Date' => 'Creation Date',
			'Creation' => 'Creation',
			'Modified Date' => 'Modified Date',
			'Modified' => 'Modified',
		
		*/
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

		$criteria->compare('t.convert_id',strtolower($this->convert_id),true);
		if(isset($_GET['type']) && $_GET['type'] == 'publish')
			$criteria->compare('t.publish',1);
		elseif(isset($_GET['type']) && $_GET['type'] == 'unpublish')
			$criteria->compare('t.publish',0);
		elseif(isset($_GET['type']) && $_GET['type'] == 'trash')
			$criteria->compare('t.publish',2);
		else {
			$criteria->addInCondition('t.publish',array(0,1));
			$criteria->compare('t.publish',$this->publish);
		}
		if(isset($_GET['location']))
			$criteria->compare('t.location_id',$_GET['location']);
		else
			$criteria->compare('t.location_id',$this->location_id);
		if(isset($_GET['category']))
			$criteria->compare('t.category_id',$_GET['category']);
		else
			$criteria->compare('t.category_id',$this->category_id);
		$criteria->compare('t.convert_parent',strtolower($this->convert_parent),true);
		$criteria->compare('t.convert_title',strtolower($this->convert_title),true);
		$criteria->compare('t.convert_desc',strtolower($this->convert_desc),true);
		$criteria->compare('t.convert_cat_id',$this->convert_cat_id);
		$criteria->compare('t.convert_numbers',strtolower($this->convert_numbers),true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		if(isset($_GET['creation']))
			$criteria->compare('t.creation_id',$_GET['creation']);
		else
			$criteria->compare('t.creation_id',$this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		if(isset($_GET['modified']))
			$criteria->compare('t.modified_id',$_GET['modified']);
		else
			$criteria->compare('t.modified_id',$this->modified_id);
		$criteria->compare('t.convert_total',$this->convert_total);
		
		// Custom Search
		$criteria->with = array(
			'creation_relation' => array(
				'alias'=>'creation_relation',
				'select'=>'displayname',
			),
			'modified_relation' => array(
				'alias'=>'modified_relation',
				'select'=>'displayname',
			),
		);
		$criteria->compare('creation_relation.displayname',strtolower($this->creation_search), true);
		$criteria->compare('modified_relation.displayname',strtolower($this->modified_search), true);

		if(!isset($_GET['ArchiveConverts_sort']))
			$criteria->order = 't.convert_id DESC';

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
			//$this->defaultColumns[] = 'convert_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'location_id';
			$this->defaultColumns[] = 'category_id';
			$this->defaultColumns[] = 'convert_parent';
			$this->defaultColumns[] = 'convert_title';
			$this->defaultColumns[] = 'convert_desc';
			$this->defaultColumns[] = 'convert_cat_id';
			$this->defaultColumns[] = 'convert_numbers';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_id';
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
			$this->defaultColumns[] = 'convert_title';
			$this->defaultColumns[] = array(
				'name' => 'location_id',
				'value' => '$data->location->location_name',
				'filter' => ArchiveLocation::getLocation(),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'category_id',
				'value' => '$data->category->category_name',
				'filter' => ArchiveConvertCategory::getCategory(),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'convert_cat_id',
				'value' => 'ArchiveSettings::getInfo(1, "auto_numbering") == 1 ? 0 : $data->convert_cat_id',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'name' => 'convert_parent',
				'value' => '$data->convert_parent != 0 ? $data->convert_parent : "-"',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'name' => 'convert_total',
				'value' => '$data->convert_total',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			/*
			$this->defaultColumns[] = array(
				'name' => 'creation_search',
				'value' => '$data->creation_relation->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Utility::dateFormat($data->creation_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'creation_date',
					'language' => 'ja',
					'i18nScriptFile' => 'jquery.ui.datepicker-en.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'creation_date_filter',
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
			*/
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->convert_id)), $data->publish, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=>array(
						1=>Yii::t('phrase', 'Yes'),
						0=>Yii::t('phrase', 'No'),
					),
					'type' => 'raw',
				);
			}
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id,array(
				'select' => $column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;			
		}
	}

	/**
	 * get Item Archive
	 */
	public static function getItemArchive($data)
	{
		$convert_number = unserialize($data);
		if(!empty($convert_number)) {
			$item = (trim($convert_number['finish'])-trim($convert_number['start']));
			$return = $item == 0 ? $item : $item+1;
			
		} else
			$return = 0;
		
		return $return;
	}

	/**
	 * get Detail Item Archive
	 */
	public static function getDetailItemArchive($data)
	{
		$return = implode('-', $data);		
		return $return;
	}
	
	protected function afterFind() 
	{
		$this->convert_total = self::getItemArchive($this->convert_numbers);
		
		parent::afterFind();		
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = Yii::app()->user->id;
			else
				$this->modified_id = Yii::app()->user->id;
			
			if(empty($this->convert_number) || $this->convert_number == null)
				$this->addError('convert_number', 'Number cannot be blank.');
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		$action = strtolower(Yii::app()->controller->action->id);
		
		if(parent::beforeSave()) {
			if($action != 'publish')
				$this->convert_numbers = serialize($this->convert_number);
		}
		return true;
	}

}