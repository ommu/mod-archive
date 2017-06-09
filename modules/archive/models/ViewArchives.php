<?php
/**
 * ViewArchives
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 17 June 2016, 05:42 WIB
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
 * This is the model class for table "_view_archives".
 *
 * The followings are the available columns in table '_view_archives':
 * @property string $archive_id
 * @property integer $converts
 * @property integer $convert_all
 * @property integer $story_enable
 * @property integer $type_enable
 * @property string $location_code
 * @property string $story_code
 * @property string $type_code
 * @property integer $archive_type_id
 */
class ViewArchives extends CActiveRecord
{
	public $defaultColumns = array();
	public $archive_code;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewArchives the static model class
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
		return '_view_archives';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'archive_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('archive_type_id', 'required'),
			array('converts, convert_all, story_enable, type_enable, archive_type_id', 'numerical', 'integerOnly'=>true),
			array('archive_id', 'length', 'max'=>11),
			array('location_code, story_code, type_code', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('archive_id, converts, convert_all, story_enable, type_enable, location_code, story_code, type_code, archive_type_id,
				archive_code', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'archive_id' => Yii::t('attribute', 'Archive'),
			'converts' => Yii::t('attribute', 'Converts'),
			'convert_all' => Yii::t('attribute', 'Convert All'),
			'story_enable' => Yii::t('attribute', 'Story Enable'),
			'type_enable' => Yii::t('attribute', 'Type Enable'),
			'location_code' => Yii::t('attribute', 'Location Code'),
			'story_code' => Yii::t('attribute', 'Story Code'),
			'type_code' => Yii::t('attribute', 'Type Code'),
			'archive_type_id' => Yii::t('attribute', 'Archive Type ID'),
			'archive_code' => Yii::t('attribute', 'Code'),
		);
		/*
			'Archive' => 'Archive',
			'Story Enable' => 'Story Enable',
			'Type Enable' => 'Type Enable',
			'Location Code' => 'Location Code',
			'Story Code' => 'Story Code',
			'Type Code' => 'Type Code',
			'Archive Type' => 'Archive Type',
		
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

		$criteria->compare('t.archive_id',$this->archive_id);
		$criteria->compare('t.converts',$this->converts);
		$criteria->compare('t.convert_all',$this->convert_all);
		$criteria->compare('t.story_enable',$this->story_enable);
		$criteria->compare('t.type_enable',$this->type_enable);
		$criteria->compare('t.location_code',strtolower($this->location_code),true);
		$criteria->compare('t.story_code',strtolower($this->story_code),true);
		$criteria->compare('t.type_code',strtolower($this->type_code),true);
		$criteria->compare('t.archive_type_id',$this->archive_type_id);
		$criteria->compare('t.archive_code',$this->archive_code,true);

		if(!isset($_GET['ViewArchives_sort']))
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
			$this->defaultColumns[] = 'archive_id';
			$this->defaultColumns[] = 'converts';
			$this->defaultColumns[] = 'convert_all';
			$this->defaultColumns[] = 'story_enable';
			$this->defaultColumns[] = 'type_enable';
			$this->defaultColumns[] = 'location_code';
			$this->defaultColumns[] = 'story_code';
			$this->defaultColumns[] = 'type_code';
			$this->defaultColumns[] = 'archive_type_id';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			//$this->defaultColumns[] = 'archive_id';
			$this->defaultColumns[] = 'converts';
			$this->defaultColumns[] = 'convert_all';
			$this->defaultColumns[] = 'archive_code';
			$this->defaultColumns[] = 'story_enable';
			$this->defaultColumns[] = 'type_enable';
			$this->defaultColumns[] = 'location_code';
			$this->defaultColumns[] = 'story_code';
			$this->defaultColumns[] = 'type_code';
			$this->defaultColumns[] = 'archive_type_id';
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
	public static function getCodeArchive($condition, $location, $story, $type, $id)
	{
		if(ArchiveSettings::getInfo('auto_numbering') == 1)
			$id = 0;
		else
			$id = $id;
		$archive_code = array($location);
		if($condition['story'] == 1)
			array_push($archive_code, $story);
		if($condition['type'] == 1)
			array_push($archive_code, $type.$id);
		else
			array_push($archive_code, $id);
		
		return implode(".", $archive_code);
	}
	
	protected function afterFind() 
	{
		$this->archive_code = self::getCodeArchive(array('story'=>$this->story_enable,'type'=>$this->type_enable), $this->location_code, $this->story_code, $this->type_code, $this->archive_type_id);
		
		parent::afterFind();		
	}

}