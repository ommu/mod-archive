<?php
/**
 * ArchiveLocation
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 13 June 2016, 23:50 WIB
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
 * This is the model class for table "ommu_archive_location".
 *
 * The followings are the available columns in table 'ommu_archive_location':
 * @property integer $location_id
 * @property integer $publish
 * @property string $location_name
 * @property string $location_desc
 * @property string $location_code
 * @property integer $story_enable
 * @property integer $type_enable
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 */
class ArchiveLocation extends CActiveRecord
{
	public $defaultColumns = array();
	public $archive_total;
	
	// Variable Search
	public $creation_search;
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArchiveLocation the static model class
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
		return 'ommu_archive_location';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('publish, location_name, location_code', 'required'),
			array('publish, story_enable, type_enable', 'numerical', 'integerOnly'=>true),
			array('location_name', 'length', 'max'=>32),
			array('location_code', 'length', 'max'=>8),
			array('creation_id, modified_id', 'length', 'max'=>11),
			array('location_desc', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('location_id, publish, location_name, location_desc, location_code, story_enable, type_enable, creation_date, creation_id, modified_date, modified_id,
				archive_total, creation_search, modified_search', 'safe', 'on'=>'search'),
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
			'archives' => array(self::HAS_MANY, 'Archives', 'location_id'),
			'archive_publish' => array(self::HAS_MANY, 'Archives', 'location_id', 'on'=>'archive_publish.publish = 1'),
			'archive_unpublish' => array(self::HAS_MANY, 'Archives', 'location_id', 'on'=>'archive_unpublish.publish = 1'),
			'creation_relation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified_relation' => array(self::BELONGS_TO, 'Users', 'modified_id'),
			'view' => array(self::BELONGS_TO, 'ViewArchiveLocation', 'location_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'location_id' => Yii::t('attribute', 'Location'),
			'publish' => Yii::t('attribute', 'Publish'),
			'location_name' => Yii::t('attribute', 'Name'),
			'location_desc' => Yii::t('attribute', 'Descripstion'),
			'location_code' => Yii::t('attribute', 'Code'),
			'story_enable' => Yii::t('attribute', 'Story'),
			'type_enable' => Yii::t('attribute', 'Type'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'archive_total' => Yii::t('attribute', 'Total'),
		);
		/*
			'Location' => 'Location',
			'Publish' => 'Publish',
			'Location Name' => 'Location Name',
			'Location Desc' => 'Location Desc',
			'Location Code' => 'Location Code',
			'Story Enable' => 'Story Enable',
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

		$criteria->compare('t.location_id',$this->location_id);
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
		$criteria->compare('t.location_name',strtolower($this->location_name),true);
		$criteria->compare('t.location_desc',strtolower($this->location_desc),true);
		$criteria->compare('t.location_code',strtolower($this->location_code),true);
		$criteria->compare('t.story_enable',$this->story_enable);
		$criteria->compare('t.type_enable',$this->type_enable);
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
		$criteria->compare('t.archive_total',$this->archive_total);
		
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

		if(!isset($_GET['ArchiveLocation_sort']))
			$criteria->order = 't.location_id DESC';

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
			//$this->defaultColumns[] = 'location_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'location_name';
			$this->defaultColumns[] = 'location_desc';
			$this->defaultColumns[] = 'location_code';
			$this->defaultColumns[] = 'story_enable';
			$this->defaultColumns[] = 'type_enable';
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
			$this->defaultColumns[] = 'location_name';
			$this->defaultColumns[] = 'location_desc';
			$this->defaultColumns[] = array(
				'name' => 'location_code',
				'value' => 'strtoupper($data->location_code)',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'header' => Yii::t('attribute', 'Archives'),
				'value' => 'CHtml::link($data->view->archives, Yii::app()->controller->createUrl("o/admin/manage",array("location"=>$data->location_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'archive_total',
				'value' => '$data->archive_total',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
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
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'story_enable',
					'value' => '$data->story_enable == 1 ? Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
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
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'type_enable',
					'value' => '$data->type_enable == 1 ? Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : Chtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\')',
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
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->location_id)), $data->publish, 1)',
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
	 * Get Location
	 */
	public static function getLocation($publish=null, $type=null) 
	{		
		$criteria=new CDbCriteria;
		if($publish != null)
			$criteria->compare('t.publish',$publish);
		$model = self::model()->findAll($criteria);

		if($type == null) {
			$items = array();
			if($model != null) {
				foreach($model as $key => $val)
					$items[$val->location_id] = $val->location_name.' ('.strtoupper($val->location_code).')';
				return $items;
				
			} else
				return false;
			
		} else
			return $model;
	}
	
	protected function afterFind() {
		$this->archive_total = Archives::getTotalItemArchive($this->archives());
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
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		if(parent::beforeSave()) {
			$this->location_code = strtolower($this->location_code);
		}
		return true;
	}

}