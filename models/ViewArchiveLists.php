<?php
/**
 * ViewArchiveLists
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 17 June 2016, 05:42 WIB
 * @link https://github.com/ommu/ommu-archive
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
 * This is the model class for table "_view_archive_lists".
 *
 * The followings are the available columns in table '_view_archive_lists':
 * @property string $list_id
 * @property integer $converts
 * @property integer $convert_all
 * @property integer $story_enable
 * @property integer $type_enable
 * @property string $location_code
 * @property string $story_code
 * @property string $type_code
 * @property integer $list_type_id
 */
class ViewArchiveLists extends CActiveRecord
{
	public $defaultColumns = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewArchiveLists the static model class
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
		return '_view_archive_lists';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'list_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('list_type_id', 'required'),
			array('converts, convert_all, story_enable, type_enable, list_type_id', 'numerical', 'integerOnly'=>true),
			array('list_id', 'length', 'max'=>11),
			array('location_code, story_code, type_code', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('list_id, converts, convert_all, story_enable, type_enable, location_code, story_code, type_code, list_type_id', 'safe', 'on'=>'search'),
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
			'list_id' => Yii::t('attribute', 'Archive'),
			'converts' => Yii::t('attribute', 'Converts'),
			'convert_all' => Yii::t('attribute', 'Convert All'),
			'story_enable' => Yii::t('attribute', 'Story Enable'),
			'type_enable' => Yii::t('attribute', 'Type Enable'),
			'location_code' => Yii::t('attribute', 'Location Code'),
			'story_code' => Yii::t('attribute', 'Story Code'),
			'type_code' => Yii::t('attribute', 'Type Code'),
			'list_type_id' => Yii::t('attribute', 'Archive Type ID'),
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

		$criteria->compare('t.list_id', $this->list_id);
		$criteria->compare('t.converts', $this->converts);
		$criteria->compare('t.convert_all', $this->convert_all);
		$criteria->compare('t.story_enable', $this->story_enable);
		$criteria->compare('t.type_enable', $this->type_enable);
		$criteria->compare('t.location_code', strtolower($this->location_code), true);
		$criteria->compare('t.story_code', strtolower($this->story_code), true);
		$criteria->compare('t.type_code', strtolower($this->type_code), true);
		$criteria->compare('t.list_type_id', $this->list_type_id);

		if(!Yii::app()->getRequest()->getParam('ViewArchiveLists_sort'))
			$criteria->order = 't.list_id DESC';

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
			$this->defaultColumns[] = 'list_id';
			$this->defaultColumns[] = 'converts';
			$this->defaultColumns[] = 'convert_all';
			$this->defaultColumns[] = 'story_enable';
			$this->defaultColumns[] = 'type_enable';
			$this->defaultColumns[] = 'location_code';
			$this->defaultColumns[] = 'story_code';
			$this->defaultColumns[] = 'type_code';
			$this->defaultColumns[] = 'list_type_id';
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
			//$this->defaultColumns[] = 'list_id';
			$this->defaultColumns[] = 'converts';
			$this->defaultColumns[] = 'convert_all';
			$this->defaultColumns[] = 'story_enable';
			$this->defaultColumns[] = 'type_enable';
			$this->defaultColumns[] = 'location_code';
			$this->defaultColumns[] = 'story_code';
			$this->defaultColumns[] = 'type_code';
			$this->defaultColumns[] = 'list_type_id';
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id, array(
				'select' => $column,
			));
			if(count(explode(',', $column)) == 1)
				return $model->$column;
			else
				return $model;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

}