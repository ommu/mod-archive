<?php
/**
 * ArchiveConverts
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2016 OMMU (www.ommu.id)
 * @created date 19 June 2016, 01:22 WIB
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
 * This is the model class for table "ommu_archive_converts".
 *
 * The followings are the available columns in table 'ommu_archive_converts':
 * @property string $convert_id
 * @property integer $publish
 * @property integer $location_id
 * @property integer $category_id
 * @property string $convert_parent
 * @property string $convert_title
 * @property string $convert_desc
 * @property integer $convert_cat_id
 * @property string $convert_publish_year
 * @property integer $convert_multiple
 * @property string $convert_copies
 * @property string $convert_code
 * @property string $archive_numbers
 * @property string $archive_total
 * @property string $archive_pages
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property ArchiveListConvert[] $ArchiveListConverts
 * @property ArchiveConvertCategory $category
 */
class ArchiveConverts extends CActiveRecord
{
	use GridViewTrait;
	
	public $defaultColumns = array();
	public $convert_parent_title_i;
	public $back_field_i;
	public $archive_number_single_i;
	public $archive_number_multiple_i;
	
	// Variable Search
	public $convert_code_search;
	public $creation_search;
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArchiveConverts the static model class
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
		return 'ommu_archive_converts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('location_id, category_id, convert_title, convert_publish_year', 'required'),
			array('convert_cat_id', 'required', 'on'=>'not_auto_numbering'),
			array('publish, location_id, category_id, convert_parent, convert_cat_id, convert_multiple,
				back_field_i', 'numerical', 'integerOnly'=>true),
			array('convert_publish_year', 'length', 'max'=>4),
			array('archive_total, archive_pages, convert_copies, creation_id, modified_id', 'length', 'max'=>11),
			array('convert_code', 'length', 'max'=>32),
			array('convert_parent, convert_desc, convert_multiple, convert_copies, convert_code, archive_numbers, archive_total, archive_pages,
				convert_parent_title_i, archive_number_single_i, archive_number_multiple_i', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('convert_id, publish, location_id, category_id, convert_parent, convert_title, convert_desc, convert_cat_id, convert_publish_year, convert_multiple, convert_copies, convert_code, archive_numbers, archive_total, archive_pages, creation_date, creation_id, modified_date, modified_id,
				convert_total, convert_code_search, creation_search, modified_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'ViewArchiveConverts', 'convert_id'),
			'location' => array(self::BELONGS_TO, 'ArchiveLocation', 'location_id'),
			'category' => array(self::BELONGS_TO, 'ArchiveConvertCategory', 'category_id'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
			'medias' => array(self::HAS_MANY, 'ArchiveListConvert', 'convert_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'convert_id' => Yii::t('attribute', 'Convert'),
			'publish' => Yii::t('attribute', 'Publish'),
			'location_id' => Yii::t('attribute', 'Location'),
			'category_id' => Yii::t('attribute', 'Category'),
			'convert_parent' => Yii::t('attribute', 'Parent'),
			'convert_title' => Yii::t('attribute', 'Title'),
			'convert_desc' => Yii::t('attribute', 'Description'),
			'convert_cat_id' => Yii::t('attribute', 'ID (Number)'),
			'convert_publish_year' => Yii::t('attribute', 'Publish Year'),
			'convert_multiple' => Yii::t('attribute', 'Is Multiple Convert'),
			'convert_copies' => Yii::t('attribute', 'Copies'),
			'convert_code' => Yii::t('attribute', 'Code'),
			'archive_numbers' => Yii::t('attribute', 'Archive Numbers'),
			'archive_total' => Yii::t('attribute', 'Archives'),
			'archive_pages' => Yii::t('attribute', 'Archive Pages'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'convert_parent_title_i' => Yii::t('attribute', 'Parent Title'),
			'back_field_i' => Yii::t('attribute', 'Back to Manage'),
			'convert_code_search' => Yii::t('attribute', 'Code'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
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
			'creation' => array(
				'alias' => 'creation',
				'select' => 'displayname',
			),
			'modified' => array(
				'alias' => 'modified',
				'select' => 'displayname',
			),
			'view' => array(
				'alias' => 'view',
			),
		);

		$criteria->compare('t.convert_id', strtolower($this->convert_id), true);
		if(Yii::app()->getRequest()->getParam('type') == 'publish')
			$criteria->compare('t.publish', 1);
		elseif(Yii::app()->getRequest()->getParam('type') == 'unpublish')
			$criteria->compare('t.publish', 0);
		elseif(Yii::app()->getRequest()->getParam('type') == 'trash')
			$criteria->compare('t.publish', 2);
		else {
			$criteria->addInCondition('t.publish', array(0,1));
			$criteria->compare('t.publish', $this->publish);
		}
		if(Yii::app()->getRequest()->getParam('location'))
			$criteria->compare('t.location_id', Yii::app()->getRequest()->getParam('location'));
		else
			$criteria->compare('t.location_id', $this->location_id);
		if(Yii::app()->getRequest()->getParam('category'))
			$criteria->compare('t.category_id', Yii::app()->getRequest()->getParam('category'));
		else
			$criteria->compare('t.category_id', $this->category_id);
		$criteria->compare('t.convert_parent', $this->convert_parent);
		$criteria->compare('t.convert_title', strtolower($this->convert_title), true);
		$criteria->compare('t.convert_desc', strtolower($this->convert_desc), true);
		$criteria->compare('t.convert_cat_id', $this->convert_cat_id);
		$criteria->compare('t.convert_publish_year', strtolower($this->convert_publish_year), true);
		$criteria->compare('t.convert_multiple', $this->convert_multiple);
		$criteria->compare('t.convert_copies', $this->convert_copies);
		$criteria->compare('t.convert_code', $this->convert_code,true);
		$criteria->compare('t.archive_numbers', strtolower($this->archive_numbers), true);
		$criteria->compare('t.archive_total', $this->archive_total);
		$criteria->compare('t.archive_pages', $this->archive_pages);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.creation_date)', date('Y-m-d', strtotime($this->creation_date)));
		if(Yii::app()->getRequest()->getParam('creation'))
			$criteria->compare('t.creation_id', Yii::app()->getRequest()->getParam('creation'));
		else
			$criteria->compare('t.creation_id', $this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		if(Yii::app()->getRequest()->getParam('modified'))
			$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified'));
		else
			$criteria->compare('t.modified_id', $this->modified_id);
		
		$criteria->compare('view.convert_code', strtolower($this->convert_code_search), true);		
		$criteria->compare('creation.displayname', strtolower($this->creation_search), true);
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);

		if(!Yii::app()->getRequest()->getParam('ArchiveConverts_sort'))
			$criteria->order = 't.convert_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
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

		if(Yii::app()->getRequest()->getParam('location'))
			$criteria->compare('t.location_id', Yii::app()->getRequest()->getParam('location'));
		else
			$criteria->compare('t.location_id', $this->location_id);
		if(Yii::app()->getRequest()->getParam('category'))
			$criteria->compare('t.category_id', Yii::app()->getRequest()->getParam('category'));
		else
			$criteria->compare('t.category_id', $this->category_id);		
		if(!Yii::app()->request->isAjaxRequest && Yii::app()->getRequest()->getParam('title'))
			$criteria->compare('t.convert_title', strtolower(Yii::app()->getRequest()->getParam('title')), true);
		else
			$criteria->compare('t.convert_title', strtolower($this->convert_title), true);
		$criteria->compare('t.convert_desc', strtolower($this->convert_desc), true);
		$criteria->compare('t.convert_publish_year', strtolower($this->convert_publish_year), true);

		if(!Yii::app()->getRequest()->getParam('ArchiveConverts_sort'))
			$criteria->order = 't.convert_id DESC';

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
			//$this->defaultColumns[] = 'convert_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'location_id';
			$this->defaultColumns[] = 'category_id';
			$this->defaultColumns[] = 'convert_parent';
			$this->defaultColumns[] = 'convert_title';
			$this->defaultColumns[] = 'convert_desc';
			$this->defaultColumns[] = 'convert_cat_id';
			$this->defaultColumns[] = 'convert_publish_year';
			$this->defaultColumns[] = 'convert_multiple';
			$this->defaultColumns[] = 'convert_copies';
			$this->defaultColumns[] = 'convert_code';
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
				'name' => 'convert_code',
				'value' => 'strtoupper($data->convert_code)',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = 'convert_title';
			$this->defaultColumns[] = array(
				'name' => 'location_id',
				'value' => '$data->location->location_name',
				'filter' => ArchiveLocation::getLocation(),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'category_id',
				'value' => '$data->category->category_name',
				'filter' => ArchiveConvertCategory::getCategory(),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'convert_cat_id',
				'value' => 'ArchiveSettings::getInfo(\'auto_numbering\') == 1 ? 0 : $data->convert_cat_id',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->defaultColumns[] = array(
				'name' => 'convert_publish_year',
				'value' => '$data->convert_publish_year',
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
				'name' => 'convert_copies',
				'value' => '$data->convert_copies',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			*/
			/*
			$this->defaultColumns[] = array(
				'name' => 'creation_search',
				'value' => '$data->creation->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Yii::app()->dateFormatter->formatDateTime($data->creation_date, \'medium\', false)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'creation_date'),
			);
			*/
			if(!Yii::app()->getRequest()->getParam('type')) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'publish\', array(\'id\'=>$data->convert_id)), $data->publish, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter' => $this->filterYesNo(),
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

	/**
	 * get Code/Number Archive
	 */
	public static function getConvertCode($location, $category, $id, $parent_id)
	{
		if(ArchiveSettings::getInfo('auto_numbering') == 1)
			$id = 0;
		else
			$id = $id;
		$convert_code = array($location);
		array_push($convert_code, $category);
		if($id != 0)
			array_push($convert_code, $id);
		else
			array_push($convert_code, $parent_id);
		
		return implode(".", $convert_code);
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
					if($convert == 'item') {
						$item = (trim($val['finish'])-trim($val['start']));
						$data_plus = $item == 0 ? $item : $item+1;
						$return = $return + $data_plus;
					} else {
						$return = $return + (trim($val['pages']));
					}
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
	
	protected function afterFind() 
	{
		//if($this->convert_parent != 0)
		//	$this->convert_cat_id = self::model()->findByPk($this->convert_parent)->convert_cat_id;
		
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
			
			if($this->convert_multiple == 0 && (empty($this->archive_number_single_i) || $this->archive_number_single_i == null))
				$this->addError('archive_number_single_i', 'Number Single cannot be blank.');
			if($this->convert_multiple == 1 && (empty($this->archive_number_multiple_i) || $this->archive_number_multiple_i == null))
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
			$parent_convert_cat_id = $this->convert_parent != 0 ? ArchiveConverts::model()->findByPk($this->convert_parent)->convert_cat_id : '';
			$this->convert_code = self::getConvertCode($this->location->location_code, $this->category->category_code, $this->convert_cat_id, $parent_convert_cat_id);
			
			if(in_array($controller, array('o/convert'))) {
				if($this->convert_parent != 0)
					$this->convert_cat_id = 0;				
				
				if($this->convert_multiple == 0)
					$this->archive_numbers = serialize($this->archive_number_single_i);				
				else
					$this->archive_numbers = serialize($this->archive_number_multiple_i);				
			}
			
			if(in_array($controller, array('o/convert','sync'))) {				
				if($this->convert_multiple == 1)
					$this->archive_pages = self::getItemArchive($this->archive_numbers, $this->convert_multiple, 'pages');	
				$this->archive_total = self::getItemArchive($this->archive_numbers, $this->convert_multiple);				
			}
		}
		return true;
	}

}