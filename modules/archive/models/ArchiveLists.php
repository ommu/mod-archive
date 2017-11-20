<?php
/**
 * ArchiveLists
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
 * This is the model class for table "ommu_archive_lists".
 *
 * The followings are the available columns in table 'ommu_archive_lists':
 * @property string $list_id
 * @property integer $publish
 * @property integer $location_id
 * @property integer $type_id
 * @property integer $story_id
 * @property string $list_title
 * @property string $list_desc
 * @property integer $list_type_id
 * @property string $list_publish_year
 * @property integer $list_multiple
 * @property string $list_copies
 * @property string $list_code
 * @property string $archive_numbers
 * @property string $archive_total
 * @property string $archive_pages
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property ArchiveType $type
 */
class ArchiveLists extends CActiveRecord
{
	public $defaultColumns = array();
	public $back_field_i;
	public $archive_number_single_i;
	public $archive_number_multiple_i;
	
	// Variable Search
	public $convert_search;
	public $creation_search;
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArchiveLists the static model class
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
		return 'ommu_archive_lists';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('location_id, list_title, list_publish_year', 'required'),
			array('list_type_id', 'required', 'on'=>'not_auto_numbering'),
			array('publish, location_id, type_id, story_id, list_type_id, list_multiple,
				back_field_i', 'numerical', 'integerOnly'=>true),
			array('list_publish_year', 'length', 'max'=>4),
			array('archive_total, archive_pages, list_copies, creation_id, modified_id', 'length', 'max'=>11),
			array('list_code', 'length', 'max'=>32),
			array('type_id, story_id, list_desc, list_multiple, list_copies, list_code, archive_numbers, archive_total, archive_pages,
				archive_number_single_i, archive_number_multiple_i', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('list_id, publish, location_id, type_id, story_id, list_title, list_desc, list_type_id, list_publish_year, list_multiple, list_copies, list_code, archive_numbers, archive_total, archive_pages, creation_date, creation_id, modified_date, modified_id,
				convert_search, creation_search, modified_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'ViewArchiveLists', 'list_id'),
			'location' => array(self::BELONGS_TO, 'ArchiveLocation', 'location_id'),
			'type' => array(self::BELONGS_TO, 'ArchiveType', 'type_id'),
			'story' => array(self::BELONGS_TO, 'ArchiveStory', 'story_id'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'list_id' => Yii::t('attribute', 'Archive'),
			'publish' => Yii::t('attribute', 'Publish'),
			'location_id' => Yii::t('attribute', 'Location'),
			'type_id' => Yii::t('attribute', 'Type'),
			'story_id' => Yii::t('attribute', 'Story'),
			'list_title' => Yii::t('attribute', 'Title'),
			'list_desc' => Yii::t('attribute', 'Description'),
			'list_type_id' => Yii::t('attribute', 'ID (Number)'),
			'list_publish_year' => Yii::t('attribute', 'Publish Year'),
			'list_multiple' => Yii::t('attribute', 'Is Multiple Archive'),
			'list_copies' => Yii::t('attribute', 'Copies'),
			'list_code' => Yii::t('attribute', 'Code'),
			'archive_numbers' => Yii::t('attribute', 'Archive Numbers'),
			'archive_total' => Yii::t('attribute', 'Archives'),
			'archive_pages' => Yii::t('attribute', 'Archive Pages'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'convert_search' => Yii::t('attribute', 'Alih'),
			'back_field_i' => Yii::t('attribute', 'Back to Manage'),
			'archive_number_single_i' => Yii::t('attribute', 'Archive Number'),
			'archive_number_multiple_i' => Yii::t('attribute', 'Archive Number'),
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
			'view' => array(
				'alias'=>'view',
			),
			'creation' => array(
				'alias'=>'creation',
				'select'=>'displayname',
			),
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname',
			),
		);

		$criteria->compare('t.list_id',strtolower($this->list_id),true);
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
		$criteria->compare('t.list_title',strtolower($this->list_title),true);
		$criteria->compare('t.list_desc',strtolower($this->list_desc),true);
		$criteria->compare('t.list_type_id',$this->list_type_id);
		$criteria->compare('t.list_publish_year',strtolower($this->list_publish_year),true);
		$criteria->compare('t.list_multiple',$this->list_multiple);
		$criteria->compare('t.list_copies',$this->list_copies);
		$criteria->compare('t.list_code',$this->list_code,true);
		$criteria->compare('t.archive_numbers',strtolower($this->archive_numbers),true);
		$criteria->compare('t.archive_total',$this->archive_total);
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
			
		$criteria->compare('view.converts',$this->convert_search);
		$criteria->compare('creation.displayname',strtolower($this->creation_search),true);
		$criteria->compare('modified.displayname',strtolower($this->modified_search),true);

		if(!isset($_GET['ArchiveLists_sort']))
			$criteria->order = 't.list_id DESC';
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=> 30,
			),
		));
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
	public function frontSearch()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

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
		if(!Yii::app()->request->isAjaxRequest && isset($_GET['title']))
			$criteria->compare('t.list_title',strtolower($_GET['title']),true);
		else
			$criteria->compare('t.list_title',strtolower($this->list_title),true);
		$criteria->compare('t.list_desc',strtolower($this->list_desc),true);
		$criteria->compare('t.list_publish_year',strtolower($this->list_publish_year),true);

		if(!isset($_GET['ArchiveLists_sort']))
			$criteria->order = 't.list_id DESC';
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>10,
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
			//$this->defaultColumns[] = 'list_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'location_id';
			$this->defaultColumns[] = 'type_id';
			$this->defaultColumns[] = 'story_id';
			$this->defaultColumns[] = 'list_title';
			$this->defaultColumns[] = 'list_desc';
			$this->defaultColumns[] = 'list_type_id';
			$this->defaultColumns[] = 'list_publish_year';
			$this->defaultColumns[] = 'list_multiple';
			$this->defaultColumns[] = 'list_copies';
			$this->defaultColumns[] = 'list_code';
			$this->defaultColumns[] = 'archive_numbers';
			$this->defaultColumns[] = 'archive_total';
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
				'name' => 'list_code',
				'value' => 'strtoupper($data->list_code)',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = 'list_title';
			$this->defaultColumns[] = array(
				'name' => 'location_id',
				'value' => '$data->location->location_name',
				'filter' => ArchiveLocation::getLocation(),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'story_id',
				'value' => '$data->story_id ? $data->story->story_name : "-"',
				'filter' => ArchiveStory::getStory(),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'type_id',
				'value' => '$data->type_id ? $data->type->type_name : "-"',
				'filter' => ArchiveType::getType(),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'list_type_id',
				'value' => 'ArchiveSettings::getInfo(\'auto_numbering\') == 1 ? 0 : $data->list_type_id',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'name' => 'list_publish_year',
				'value' => '$data->list_publish_year',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			/*
			$this->defaultColumns[] = array(
				'name' => 'archive_total',
				'value' => '$data->archive_total',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'name' => 'archive_pages',
				'value' => '$data->archive_pages',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'name' => 'list_copies',
				'value' => '$data->list_copies',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_search',
				'value' => '$data->creation->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Utility::dateFormat($data->creation_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'creation_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
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
			$this->defaultColumns[] = array(
				'name' => 'convert_search',
				'value' => 'CHtml::link($data->view->converts ? $data->view->converts : 0, Yii::app()->controller->createUrl("o/media/manage",array("list"=>$data->list_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'filter' => false,
				'name' => 'convert_search',
				'value' => 'CHtml::link(Yii::t("phrase", "Add Convert"), Yii::app()->controller->createUrl("o/media/add",array("list"=>$data->list_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'type' => 'raw',
			);
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->list_id)), $data->publish, 1)',
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
	 * get Code/Number Archive
	 */
	public static function getListCode($condition, $location, $story, $type, $id)
	{
		if(ArchiveSettings::getInfo('auto_numbering') == 1)
			$id = 0;
		else
			$id = $id;
		$list_code = array($location);
		if($condition['story'] == 1)
			array_push($list_code, $story);
		if($condition['type'] == 1)
			array_push($list_code, $type.$id);
		else
			array_push($list_code, $id);
		
		return implode(".", $list_code);
	}

	/**
	 * get Item Archive
	 */
	public static function getItemArchive($data, $type=0, $archive='item')
	{
		$archive_number = unserialize($data);
		if(!empty($archive_number)) {
			$count_archive_number = count($archive_number);
			if($type == 0 || ($count_archive_number == 2 && (array_key_exists('start', $archive_number) && array_key_exists('finish', $archive_number)))) {
				$item = (trim($archive_number['finish'])-trim($archive_number['start']));
				$return = $item == 0 ? $item : $item+1;
			} else {
				$return = 0;
				foreach($archive_number as $key => $val) {
					if($archive == 'item') {
						$item = (trim($val['finish'])-trim($val['start']));
						$data_plus = $item == 0 ? $item : $item+1;
						$return = $return + $data_plus;						
					} else
						$return = $return + (trim($val['pages']));
				}
			}
			
		} else
			$return = 0;
		
		return $return;
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
			if($this->list_multiple == 0 && (empty($this->archive_number_single_i) || $this->archive_number_single_i == null))
				$this->addError('archive_number_single_i', 'Number Single cannot be blank.');
			if($this->list_multiple == 1 && (empty($this->archive_number_multiple_i) || $this->archive_number_multiple_i == null))
				$this->addError('archive_number_multiple_i', 'Number Multiple cannot be blank.');
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() 
	{
		$controller = strtolower(Yii::app()->controller->id);
	
		if(parent::beforeSave()) {
			$this->list_code = self::getListCode(array('story'=>$this->location->story_enable,'type'=>$this->location->type_enable), $this->location->location_code, $this->story->story_code, $this->type->type_code, $this->list_type_id);
			
			if(in_array($controller, array('o/admin'))) {
				if($this->list_multiple == 0)
					$this->archive_numbers = serialize($this->archive_number_single_i);				
				else
					$this->archive_numbers = serialize($this->archive_number_multiple_i);
			}
			
			if(in_array($controller, array('o/admin','sync'))) {
				if($this->list_multiple == 1)
					$this->archive_pages = self::getItemArchive($this->archive_numbers, $this->list_multiple, 'pages');	
				$this->archive_total = self::getItemArchive($this->archive_numbers, $this->list_multiple);				
			}
		}
		return true;
	}

}