<?php
/**
 * ViewArchiveStory
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
 * This is the model class for table "_view_archive_story".
 *
 * The followings are the available columns in table '_view_archive_story':
 * @property integer $story_id
 * @property string $lists
 * @property string $list_all
 * @property string $copies
 * @property string $copy_all
 * @property string $archives
 * @property string $archive_all
 * @property string $archive_pages
 * @property string $archive_page_all
 */
class ViewArchiveStory extends CActiveRecord
{
	public $defaultColumns = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewArchiveStory the static model class
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
		return '_view_archive_story';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'story_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('story_id', 'numerical', 'integerOnly'=>true),
			array('lists, list_all', 'length', 'max'=>21),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('story_id, lists, list_all, copies, copy_all, archives, archive_all, archive_pages, archive_page_all', 'safe', 'on'=>'search'),
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
			'story_id' => Yii::t('attribute', 'Story'),
			'lists' => Yii::t('attribute', 'Senarai'),
			'list_all' => Yii::t('attribute', 'Senarai All'),
			'copies' => Yii::t('attribute', 'Copies'),
			'copy_all' => Yii::t('attribute', 'Copy All'),
			'archives' => Yii::t('attribute', 'Archives'),
			'archive_all' => Yii::t('attribute', 'Archive All'),
			'archive_pages' => Yii::t('attribute', 'Archive Pages'),
			'archive_page_all' => Yii::t('attribute', 'Archive Page All'),
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

		$criteria->compare('t.story_id', $this->story_id);
		$criteria->compare('t.lists', $this->lists);
		$criteria->compare('t.list_all', $this->list_all);
		$criteria->compare('t.copies', $this->copies);
		$criteria->compare('t.copy_all', $this->copy_all);
		$criteria->compare('t.archives', $this->archives);
		$criteria->compare('t.archive_all', $this->archive_all);
		$criteria->compare('t.archive_pages', $this->archive_pages);
		$criteria->compare('t.archive_page_all', $this->archive_page_all);

		if(!Yii::app()->getRequest()->getParam('ViewArchiveStory_sort'))
			$criteria->order = 't.story_id DESC';

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
			$this->defaultColumns[] = 'story_id';
			$this->defaultColumns[] = 'lists';
			$this->defaultColumns[] = 'list_all';
			$this->defaultColumns[] = 'copies';
			$this->defaultColumns[] = 'copy_all';
			$this->defaultColumns[] = 'archives';
			$this->defaultColumns[] = 'archive_all';
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
			//$this->defaultColumns[] = 'story_id';
			$this->defaultColumns[] = 'lists';
			$this->defaultColumns[] = 'list_all';
			$this->defaultColumns[] = 'copies';
			$this->defaultColumns[] = 'copy_all';
			$this->defaultColumns[] = 'archives';
			$this->defaultColumns[] = 'archive_all';
			$this->defaultColumns[] = 'archive_pages';
			$this->defaultColumns[] = 'archive_page_all';
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