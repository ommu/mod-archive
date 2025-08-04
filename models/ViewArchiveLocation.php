<?php
/**
 * ViewArchiveLocation
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2016 OMMU (www.ommu.id)
 * @created date 16 June 2016, 13:44 WIB
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
 * This is the model class for table "_view_archive_location".
 *
 * The followings are the available columns in table '_view_archive_location':
 * @property integer $location_id
 * @property string $lists
 * @property string $list_all
 * @property string $list_copies
 * @property string $list_copy_all
 * @property string $list_archives
 * @property string $list_archive_all
 * @property string $list_archive_pages
 * @property string $list_archive_page_all
 * @property string $converts
 * @property string $convert_all
 * @property string $convert_copies
 * @property string $convert_copy_all
 * @property string $convert_archives
 * @property string $convert_archive_all
 * @property string $convert_archive_pages
 * @property string $convert_archive_page_all
 * @property string $convert_unpublish
 */
class ViewArchiveLocation extends CActiveRecord
{
	public $defaultColumns = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewArchiveLocation the static model class
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
		return '_view_archive_location';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'location_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('location_id', 'numerical', 'integerOnly'=>true),
			array('list_all, convert_all', 'length', 'max'=>21),
			array('lists, converts', 'length', 'max'=>23),
			array('list_copies, list_copy_all, list_archives, list_archive_all, list_archive_pages, list_archive_page_all, convert_copies, convert_copy_all, convert_archives, convert_archive_all, convert_archive_pages, convert_archive_page_all', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('location_id, lists, list_all, list_copies, list_copy_all, list_archives, list_archive_all, list_archive_pages, list_archive_page_all, converts, convert_all, convert_copies, convert_copy_all, convert_archives, convert_archive_all, convert_archive_pages, convert_archive_page_all', 'safe', 'on'=>'search'),
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
			'location_id' => Yii::t('attribute', 'Location'),
			'lists' => Yii::t('attribute', 'Senarai'),
			'list_all' => Yii::t('attribute', 'Senarai All'),
			'list_copies' => Yii::t('attribute', 'Senarai Copies'),
			'list_copy_all' => Yii::t('attribute', 'Senarai Copy All'),
			'list_archives' => Yii::t('attribute', 'Senarai Archives'),
			'list_archive_all' => Yii::t('attribute', 'Senarai Archive All'),
			'list_archive_pages' => Yii::t('attribute', 'Senarai Archive Pages'),
			'list_archive_page_all' => Yii::t('attribute', 'Senarai Archive Page All'),
			'converts' => Yii::t('attribute', 'Alih'),
			'convert_all' => Yii::t('attribute', 'Alih All'),
			'convert_copies' => Yii::t('attribute', 'Alih Copies'),
			'convert_copy_all' => Yii::t('attribute', 'Alih Copy All'),
			'convert_archives' => Yii::t('attribute', 'Alih Archives'),
			'convert_archive_all' => Yii::t('attribute', 'Alih Archive All'),
			'convert_archive_pages' => Yii::t('attribute', 'Alih Archive Pages'),
			'convert_archive_page_all' => Yii::t('attribute', 'Alih Archive Page All'),
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

		$criteria->compare('t.location_id', $this->location_id);
		$criteria->compare('t.lists', $this->lists);
		$criteria->compare('t.list_all', $this->list_all);
		$criteria->compare('t.list_copies', $this->list_copies);
		$criteria->compare('t.list_copy_all', $this->list_copy_all);
		$criteria->compare('t.list_archives', $this->list_archives);
		$criteria->compare('t.list_archive_all', $this->list_archive_all);
		$criteria->compare('t.list_archive_pages', $this->list_archive_pages);
		$criteria->compare('t.list_archive_page_all', $this->list_archive_page_all);
		$criteria->compare('t.converts', $this->converts);
		$criteria->compare('t.convert_all', $this->convert_all);
		$criteria->compare('t.convert_copies', $this->convert_copies);
		$criteria->compare('t.convert_copy_all', $this->convert_copy_all);
		$criteria->compare('t.convert_archives', $this->convert_archives);
		$criteria->compare('t.convert_archive_all', $this->convert_archive_all);
		$criteria->compare('t.convert_archive_pages', $this->convert_archive_pages);
		$criteria->compare('t.convert_archive_page_all', $this->convert_archive_page_all);

		if(!Yii::app()->getRequest()->getParam('ViewArchiveLocation_sort'))
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
			$this->defaultColumns[] = 'location_id';
			$this->defaultColumns[] = 'lists';
			$this->defaultColumns[] = 'list_all';
			$this->defaultColumns[] = 'list_copies';
			$this->defaultColumns[] = 'list_copy_all';
			$this->defaultColumns[] = 'list_archives';
			$this->defaultColumns[] = 'list_archive_all';
			$this->defaultColumns[] = 'list_archive_pages';
			$this->defaultColumns[] = 'list_archive_page_all';
			$this->defaultColumns[] = 'converts';
			$this->defaultColumns[] = 'convert_all';
			$this->defaultColumns[] = 'convert_copies';
			$this->defaultColumns[] = 'convert_copy_all';
			$this->defaultColumns[] = 'convert_archives';
			$this->defaultColumns[] = 'convert_archive_all';
			$this->defaultColumns[] = 'convert_archive_pages';
			$this->defaultColumns[] = 'convert_archive_page_all';
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
			//$this->defaultColumns[] = 'location_id';
			$this->defaultColumns[] = 'lists';
			$this->defaultColumns[] = 'list_all';
			$this->defaultColumns[] = 'list_copies';
			$this->defaultColumns[] = 'list_copy_all';
			$this->defaultColumns[] = 'list_archives';
			$this->defaultColumns[] = 'list_archive_all';
			$this->defaultColumns[] = 'list_archive_pages';
			$this->defaultColumns[] = 'list_archive_page_all';
			$this->defaultColumns[] = 'converts';
			$this->defaultColumns[] = 'convert_all';
			$this->defaultColumns[] = 'convert_copies';
			$this->defaultColumns[] = 'convert_copy_all';
			$this->defaultColumns[] = 'convert_archives';
			$this->defaultColumns[] = 'convert_archive_all';
			$this->defaultColumns[] = 'convert_archive_pages';
			$this->defaultColumns[] = 'convert_archive_page_all';
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