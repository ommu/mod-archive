<?php
/**
 * ViewArchiveConverts
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 19 June 2016, 10:39 WIB
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
 * This is the model class for table "_view_archive_converts".
 *
 * The followings are the available columns in table '_view_archive_converts':
 * @property string $convert_id
 * @property string $location_code
 * @property string $category_code
 * @property integer $convert_cat_id
 */
class ViewArchiveConverts extends CActiveRecord
{
	public $defaultColumns = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewArchiveConverts the static model class
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
		return '_view_archive_converts';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'convert_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('convert_cat_id', 'required'),
			array('convert_cat_id', 'numerical', 'integerOnly'=>true),
			array('convert_id', 'length', 'max'=>11),
			array('location_code, category_code', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('convert_id, location_code, category_code, convert_cat_id', 'safe', 'on'=>'search'),
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
			'convert_id' => Yii::t('attribute', 'Convert'),
			'location_code' => Yii::t('attribute', 'Location Code'),
			'category_code' => Yii::t('attribute', 'Category Code'),
			'convert_cat_id' => Yii::t('attribute', 'Convert Cat'),
		);
		/*
			'Convert' => 'Convert',
			'Location Code' => 'Location Code',
			'Category Code' => 'Category Code',
			'Convert Cat' => 'Convert Cat',
		
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
		$criteria->compare('t.location_code',strtolower($this->location_code),true);
		$criteria->compare('t.category_code',strtolower($this->category_code),true);
		$criteria->compare('t.convert_cat_id',$this->convert_cat_id);

		if(!isset($_GET['ViewArchiveConverts_sort']))
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
			$this->defaultColumns[] = 'convert_id';
			$this->defaultColumns[] = 'location_code';
			$this->defaultColumns[] = 'category_code';
			$this->defaultColumns[] = 'convert_cat_id';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {/
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			//$this->defaultColumns[] = 'convert_id';
			$this->defaultColumns[] = 'location_code';
			$this->defaultColumns[] = 'category_code';
			$this->defaultColumns[] = 'convert_cat_id';
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

}