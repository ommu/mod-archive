<?php
/**
 * ViewArchiveConvertYear
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 19 June 2016, 23:28 WIB
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
 * This is the model class for table "_view_archive_convert_year".
 *
 * The followings are the available columns in table '_view_archive_convert_year':
 * @property string $publish_year
 * @property string $converts
 * @property string $convert_all
 * @property string $convert_copies
 * @property string $convert_copy_all
 * @property string $archive_pages
 * @property string $archive_page_all
 */
class ViewArchiveConvertYear extends CActiveRecord
{
	public $defaultColumns = array();
	public $convert_total_i;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewArchiveConvertYear the static model class
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
		return '_view_archive_convert_year';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'publish_year';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('publish_year', 'required'),
			array('publish_year', 'length', 'max'=>4),
			array('converts', 'length', 'max'=>23),
			array('convert_all', 'length', 'max'=>21),
			array('convert_copies, convert_copy_all, archive_pages, archive_page_all', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('publish_year, converts, convert_all, convert_copies, convert_copy_all, archive_pages, archive_page_all,
				convert_total_i', 'safe', 'on'=>'search'),
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
			'converts' => array(self::HAS_MANY, 'ArchiveConverts', 'convert_publish_year'),
			'convert_publish' => array(self::HAS_MANY, 'ArchiveConverts', 'convert_publish_year', 'on'=>'convert_publish.publish = 1'),
			'convert_unpublish' => array(self::HAS_MANY, 'ArchiveConverts', 'convert_publish_year', 'on'=>'convert_unpublish.publish = 1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'publish_year' => Yii::t('attribute', 'Publish Year'),
			'converts' => Yii::t('attribute', 'Alih'),
			'convert_all' => Yii::t('attribute', 'Alih All'),
			'convert_copies' => Yii::t('attribute', 'Copies'),
			'convert_copy_all' => Yii::t('attribute', 'Copy All'),
			'archive_pages' => Yii::t('attribute', 'Archive Pages'),
			'archive_page_all' => Yii::t('attribute', 'Archive Pages All'),
			'convert_total_i' => Yii::t('attribute', 'Archive'),
		);
		/*
			'Publish Year' => 'Publish Year',
			'Converts' => 'Converts',
		
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

		$criteria->compare('t.publish_year',strtolower($this->publish_year),true);
		$criteria->compare('t.converts',$this->converts);
		$criteria->compare('t.convert_all',$this->convert_all);
		$criteria->compare('t.convert_copies',$this->convert_copies);
		$criteria->compare('t.convert_copy_all',$this->convert_copy_all);
		$criteria->compare('t.archive_pages',$this->archive_pages);
		$criteria->compare('t.archive_page_all',$this->archive_page_all);
		$criteria->compare('t.convert_total_i',$this->convert_total_i);

		if(!isset($_GET['ViewArchiveConvertYear_sort']))
			$criteria->order = 't.publish_year DESC';

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
			$this->defaultColumns[] = 'publish_year';
			$this->defaultColumns[] = 'converts';
			$this->defaultColumns[] = 'convert_all';
			$this->defaultColumns[] = 'convert_copies';
			$this->defaultColumns[] = 'convert_copy_all';
			$this->defaultColumns[] = 'archive_pages';
			$this->defaultColumns[] = 'archive_page_all';
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
			$this->defaultColumns[] = 'publish_year';
			$this->defaultColumns[] = array(
				'name' => 'converts',
				'value' => '$data->converts',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'name' => 'convert_copies',
				'value' => '$data->convert_copies',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'filter' => false,
				'name' => 'convert_total_i',
				'value' => '$data->convert_total_i',
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
		}
		parent::afterConstruct();
	}
	
	protected function afterFind() 
	{
		$this->convert_total_i = ArchiveConverts::getTotalItemArchive($this->converts());
		
		parent::afterFind();		
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