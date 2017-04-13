<?php
/**
 * ArchiveConvertMedia
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (opensource.ommu.co)
 * @created date 19 June 2016, 01:22 WIB
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
 * This is the model class for table "ommu_archive_convert_media".
 *
 * The followings are the available columns in table 'ommu_archive_convert_media':
 * @property string $id
 * @property integer $publish
 * @property string $archive_id
 * @property string $convert_id
 * @property string $media_desc
 * @property string $creation_date
 * @property string $creation_id
 *
 * The followings are the available model relations:
 * @property OmmuArchiveConverts $convert
 * @property OmmuArchives $archive
 */
class ArchiveConvertMedia extends CActiveRecord
{
	public $defaultColumns = array();
	public $archive_code;
	public $convert_code;
	
	// Variable Search
	public $archive_search;
	public $convert_search;
	public $creation_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ArchiveConvertMedia the static model class
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
		return 'ommu_archive_convert_media';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('archive_code, convert_code', 'required'),
			array('publish', 'numerical', 'integerOnly'=>true),
			array('archive_id, convert_id, creation_id', 'length', 'max'=>11),
			array('
				archive_code, convert_code', 'length', 'max'=>32),
			array('archive_id, convert_id, media_desc', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, publish, archive_id, convert_id, media_desc, creation_date, creation_id,
				archive_code, convert_code, archive_search, convert_search, creation_search', 'safe', 'on'=>'search'),
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
			'archive' => array(self::BELONGS_TO, 'Archives', 'archive_id'),
			'convert' => array(self::BELONGS_TO, 'ArchiveConverts', 'convert_id'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'publish' => Yii::t('attribute', 'Publish'),
			'archive_id' => Yii::t('attribute', 'Archive'),
			'convert_id' => Yii::t('attribute', 'Convert'),
			'media_desc' => Yii::t('attribute', 'Description'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'archive_code' => Yii::t('attribute', 'Archive Code'),
			'convert_code' => Yii::t('attribute', 'Convert Code'),
			'archive_search' => Yii::t('attribute', 'Archive'),
			'convert_search' => Yii::t('attribute', 'Convert'),
			'creation_search' => Yii::t('attribute', 'Creation'),
		);
		/*
			'ID' => 'ID',
			'Publish' => 'Publish',
			'Archive' => 'Archive',
			'Convert' => 'Convert',
			'Media Desc' => 'Media Desc',
			'Creation Date' => 'Creation Date',
			'Creation' => 'Creation',
		
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

		$criteria->compare('t.id',strtolower($this->id),true);
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
		if(isset($_GET['archive']))
			$criteria->compare('t.archive_id',$_GET['archive']);
		else
			$criteria->compare('t.archive_id',$this->archive_id);
		if(isset($_GET['convert']))
			$criteria->compare('t.convert_id',$_GET['convert']);
		else
			$criteria->compare('t.convert_id',$this->convert_id);
		$criteria->compare('t.media_desc',strtolower($this->media_desc),true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		if(isset($_GET['creation']))
			$criteria->compare('t.creation_id',$_GET['creation']);
		else
			$criteria->compare('t.creation_id',$this->creation_id);
		
		// Custom Search
		$criteria->with = array(
			'archive' => array(
				'alias'=>'archive',
				'select'=>'archive_title, archive_code',
			),
			'convert' => array(
				'alias'=>'convert',
				'select'=>'convert_title, convert_code',
			),
			'creation' => array(
				'alias'=>'creation',
				'select'=>'displayname',
			),
		);
		$criteria->compare('archive.archive_code',strtolower($this->archive_search), true);
		$criteria->compare('convert.convert_code',strtolower($this->convert_search), true);
		$criteria->compare('creation.displayname',strtolower($this->creation_search), true);

		if(!isset($_GET['ArchiveConvertMedia_sort']))
			$criteria->order = 't.id DESC';

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
			//$this->defaultColumns[] = 'id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'archive_id';
			$this->defaultColumns[] = 'convert_id';
			$this->defaultColumns[] = 'media_desc';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_id';
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
				'name' => 'archive_search',
				'value' => 'strtoupper($data->archive->archive_code)."<br/><span>".$data->archive->archive_title."</span>"',
				'htmlOptions' => array(
					'class' => 'bold',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'convert_search',
				'value' => 'strtoupper($data->convert->convert_code)."<br/><span>".$data->convert->convert_title."</span>"',
				'htmlOptions' => array(
					'class' => 'bold',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = 'media_desc';
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
				'filter' => Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
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
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->id)), $data->publish, 1)',
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
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = Yii::app()->user->id;
		}
		return true;
	}

}