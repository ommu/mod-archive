<?php
/**
 * ViewArchiveYear
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 17 June 2016, 06:22 WIB
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
 * This is the model class for table "_view_archive_year".
 *
 * The followings are the available columns in table '_view_archive_year':
 * @property string $publish_year
 * @property string $archives
 */
class ViewArchiveYear extends CActiveRecord
{
	public $defaultColumns = array();
	public $archive_total_i;
	public $archive_page_i;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewArchiveYear the static model class
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
		return '_view_archive_year';
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
			array('archives', 'length', 'max'=>21),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('publish_year, archives,
				archive_total_i, archive_page_i', 'safe', 'on'=>'search'),
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
			'archives' => array(self::HAS_MANY, 'Archives', 'archive_publish_year'),
			'archive_publish' => array(self::HAS_MANY, 'Archives', 'archive_publish_year', 'on'=>'archive_publish.publish = 1'),
			'archive_unpublish' => array(self::HAS_MANY, 'Archives', 'archive_publish_year', 'on'=>'archive_unpublish.publish = 1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'publish_year' => Yii::t('attribute', 'Publish Year'),
			'archives' => Yii::t('attribute', 'Archives'),
			'archive_total_i' => Yii::t('attribute', 'Total'),
			'archive_page_i' => Yii::t('attribute', 'Pages'),
		);
		/*
			'Publish Year' => 'Publish Year',
			'Archives' => 'Archives',
		
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
		$criteria->compare('t.archives',$this->archives);
		$criteria->compare('t.archive_total_i',$this->archive_total_i);
		$criteria->compare('t.archive_page_i',$this->archive_page_i);

		if(!isset($_GET['ViewArchiveYear_sort']))
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
			$this->defaultColumns[] = 'archives';
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
			$this->defaultColumns[] = 'archives';
			$this->defaultColumns[] = array(
				'filter' => false,
				'name' => 'archive_total_i',
				'value' => '$data->archive_total_i',
			);
			$this->defaultColumns[] = array(
				'filter' => false,
				'name' => 'archive_page_i',
				'value' => '$data->archive_page_i',
			);
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
	
	protected function afterFind() 
	{
		$this->archive_total_i = Archives::getTotalItemArchive($this->archives());
		$this->archive_page_i = Archives::getTotalItemArchive($this->archives(), 'page');
		
		parent::afterFind();		
	}

}