<?php
/**
 * Archives
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 13 June 2016, 23:51 WIB
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
 * This is the model class for table "ommu_archives".
 *
 * The followings are the available columns in table 'ommu_archives':
 * @property string $archive_id
 * @property integer $publish
 * @property integer $location_id
 * @property integer $type_id
 * @property integer $story_id
 * @property string $archive_title
 * @property string $archive_desc
 * @property integer $archive_type_id
 * @property string $archive_publish_year
 * @property integer $archive_multiple
 * @property string $archive_numbers
 * @property string $archive_pages
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property OmmuArchiveType $type
 */
class Archives extends CActiveRecord
{
	public $defaultColumns = array();
	public $archive_total;
	public $back_field;
	public $archive_number_single;
	public $archive_number_multiple;
	
	// Variable Search
	public $archive_code_search;
	public $creation_search;
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Archives the static model class
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
		return 'ommu_archives';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('location_id, archive_title, archive_publish_year', 'required'),
			array('archive_type_id', 'required', 'on'=>'not_auto_numbering'),
			array('publish, location_id, type_id, story_id, archive_type_id, archive_multiple,
				back_field', 'numerical', 'integerOnly'=>true),
			array('archive_publish_year', 'length', 'max'=>4),
			array('archive_pages, creation_id, modified_id', 'length', 'max'=>11),
			array('type_id, story_id, archive_desc, archive_numbers, archive_pages,
				archive_number_single, archive_number_multiple', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('archive_id, publish, location_id, type_id, story_id, archive_title, archive_desc, archive_type_id, archive_publish_year, archive_multiple, archive_numbers, archive_pages, creation_date, creation_id, modified_date, modified_id,
				archive_total, archive_code_search, creation_search, modified_search', 'safe', 'on'=>'search'),
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
			'type' => array(self::BELONGS_TO, 'ArchiveType', 'type_id'),
			'story' => array(self::BELONGS_TO, 'ArchiveStory', 'story_id'),
			'creation_relation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified_relation' => array(self::BELONGS_TO, 'Users', 'modified_id'),
			'view' => array(self::BELONGS_TO, 'ViewArchives', 'archive_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'archive_id' => Yii::t('attribute', 'Archive'),
			'publish' => Yii::t('attribute', 'Publish'),
			'location_id' => Yii::t('attribute', 'Location'),
			'type_id' => Yii::t('attribute', 'Type'),
			'story_id' => Yii::t('attribute', 'Story'),
			'archive_title' => Yii::t('attribute', 'Title'),
			'archive_desc' => Yii::t('attribute', 'Description'),
			'archive_type_id' => Yii::t('attribute', 'Archive Type ID'),
			'archive_publish_year' => Yii::t('attribute', 'Publish Year'),
			'archive_multiple' => Yii::t('attribute', 'Is Multiple Archive'),
			'archive_numbers' => Yii::t('attribute', 'Numbers'),
			'archive_pages' => Yii::t('attribute', 'Pages'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'archive_total' => Yii::t('attribute', 'Total'),
			'archive_code_search' => Yii::t('attribute', 'Code'),
			'back_field' => Yii::t('attribute', 'Back to Manage'),
			'archive_number_single' => Yii::t('attribute', 'Number Single'),
			'archive_number_multiple' => Yii::t('attribute', 'Number Multiple'),
		);
		/*
			'Archive' => 'Archive',
			'Publish' => 'Publish',
			'Location' => 'Location',
			'Type' => 'Type',
			'Story' => 'Story',
			'Archive Title' => 'Archive Title',
			'Archive Desc' => 'Archive Desc',
			'Archive Type Number' => 'Archive Type Number',
			'Archive Publish Year' => 'Archive Publish Year',
			'Archive Numbers' => 'Archive Numbers',
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

		$criteria->compare('t.archive_id',strtolower($this->archive_id),true);
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
		if(isset($_GET['type']))
			$criteria->compare('t.type_id',$_GET['type']);
		else
			$criteria->compare('t.type_id',$this->type_id);
		if(isset($_GET['story']))
			$criteria->compare('t.story_id',$_GET['story']);
		else
			$criteria->compare('t.story_id',$this->story_id);
		$criteria->compare('t.archive_title',strtolower($this->archive_title),true);
		$criteria->compare('t.archive_desc',strtolower($this->archive_desc),true);
		$criteria->compare('t.archive_type_id',$this->archive_type_id);
		$criteria->compare('t.archive_publish_year',strtolower($this->archive_publish_year),true);
		$criteria->compare('t.archive_multiple',$this->archive_multiple);
		$criteria->compare('t.archive_numbers',strtolower($this->archive_numbers),true);
		$criteria->compare('t.archive_pages',$this->archive_pages);
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
		$criteria->compare('t.archive_total',$this->archive_total, true);
		
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
			'view' => array(
				'alias'=>'view',
			),
		);
		$criteria->compare('creation_relation.displayname',strtolower($this->creation_search), true);
		$criteria->compare('modified_relation.displayname',strtolower($this->modified_search), true);
		$criteria->compare('view.archive_code',strtolower($this->archive_code_search), true);

		if(!isset($_GET['Archives_sort']))
			$criteria->order = 't.archive_id DESC';

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
			//$this->defaultColumns[] = 'archive_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'location_id';
			$this->defaultColumns[] = 'type_id';
			$this->defaultColumns[] = 'story_id';
			$this->defaultColumns[] = 'archive_title';
			$this->defaultColumns[] = 'archive_desc';
			$this->defaultColumns[] = 'archive_type_id';
			$this->defaultColumns[] = 'archive_publish_year';
			$this->defaultColumns[] = 'archive_multiple';
			$this->defaultColumns[] = 'archive_numbers';
			$this->defaultColumns[] = 'archive_pages';
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
			$this->defaultColumns[] = array(
				'header' => 'archive_code_search',
				'value' => '$data->view->archive_code',
			);
			$this->defaultColumns[] = 'archive_title';
			$this->defaultColumns[] = array(
				'name' => 'location_id',
				'value' => '$data->location->location_name',
				'filter' => ArchiveLocation::getLocation(),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'story_id',
				'value' => '$data->story_id != "0" ? $data->story->story_name : "-"',
				'filter' => ArchiveStory::getStory(),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'type_id',
				'value' => '$data->type_id != "0" ? $data->type->type_name : "-"',
				'filter' => ArchiveType::getType(),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'archive_type_id',
				'value' => 'ArchiveSettings::getInfo(1, "auto_numbering") == 1 ? 0 : $data->archive_type_id',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'name' => 'archive_publish_year',
				'value' => '$data->archive_publish_year',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'header' => 'archive_total',
				'value' => '$data->archive_total',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'name' => 'archive_pages',
				'value' => '$data->archive_pages != 0 ? $data->archive_pages : "-"',
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
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->archive_id)), $data->publish, 1)',
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
	public static function getItemArchive($data, $type=0)
	{
		$archive_number = unserialize($data);
		if(!empty($archive_number)) {
			if($type == 0) {
				$item = (trim($archive_number['finish'])-trim($archive_number['start']));
				$return = $item == 0 ? $item : $item+1;
			} else {
				$return = 0;
				foreach($archive_number as $key => $val) {
					$item = (trim($val['finish'])-trim($val['start']));
					$data_plus = $item == 0 ? $item : $item+1;
					$return = $return + $data_plus;
				}
			}
			
		} else
			$return = 0;
		
		return $return;
	}

	/**
	 * get Total Item Archive
	 * $param = total, page
	 */
	public static function getTotalItemArchive($data, $param='total')
	{
		if($data != null) {
			$total = 0;
			foreach($data as $key => $val) {
				if($param == 'total')
					$total = $total + $val->archive_total;
				if($param == 'page')
					$total = $total + $val->archive_pages;
			}
			return $total;
			
		} else
			return 0;
	}

	/**
	 * get Detail Item Archive
	 */
	public static function getDetailItemArchive($data, $multiple=0)
	{
		if($multiple == 0)
			$return = implode('-', $data);
		else {
			$countData = count($data);
			$i = 0;
			foreach($data as $key => $val) {
				$i++;
				if($i != $countData)
					$return .= strtoupper($val['id']).': '.$val['start'].'-'.$val['finish'].'<br/>';
				else
					$return .= strtoupper($val['id']).': '.$val['start'].'-'.$val['finish'];
			}
		}
		
		return $return;
	}
	
	protected function afterFind() 
	{
		$this->archive_total = self::getItemArchive($this->archive_numbers, $this->archive_multiple);
		
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
			
			if($this->location->story_enable == 1 && $this->story_id == '')
				$this->addError('story_id', 'Story cannot be blank.');
			if($this->location->type_enable == 1 && $this->type_id == '')
				$this->addError('type_id', 'Type cannot be blank.');
			if($this->archive_multiple == 0 && (empty($this->archive_number_single) || $this->archive_number_single == null))
				$this->addError('archive_number_single', 'Number Single cannot be blank.');
			if($this->archive_multiple == 1 && (empty($this->archive_number_multiple) || $this->archive_number_multiple == null))
				$this->addError('archive_number_multiple', 'Number Multiple cannot be blank.');
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		if(parent::beforeSave()) {
			if($this->archive_multiple == 0)
				$this->archive_numbers = serialize($this->archive_number_single);
			else
				$this->archive_numbers = serialize($this->archive_number_multiple);
		}
		return true;
	}

}