<?php
/**
 * ArchiveLevel
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 5 March 2019, 23:32 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 * This is the model class for table "ommu_archive_level".
 *
 * The followings are the available columns in table "ommu_archive_level":
 * @property integer $id
 * @property integer $publish
 * @property integer $level_name
 * @property integer $level_desc
 * @property string $child
 * @property string $field
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Archives[] $archives
 * @property SourceMessage $title
 * @property SourceMessage $description
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\archive\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use app\models\SourceMessage;
use app\models\Users;

class ArchiveLevel extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['level_desc_i', 'child', 'field', 'creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $level_name_i;
    public $level_desc_i;

	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_archive_level';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['level_name_i', 'level_desc_i'], 'required'],
			[['publish', 'level_name', 'level_desc', 'creation_id', 'modified_id'], 'integer'],
			[['level_name_i', 'level_desc_i'], 'string'],
			[['child', 'field'], 'safe'],
			//[['child', 'field], 'serialize'],
			[['level_name_i'], 'string', 'max' => 64],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'level_name' => Yii::t('app', 'Level'),
			'level_desc' => Yii::t('app', 'Description'),
			'child' => Yii::t('app', 'Child'),
			'field' => Yii::t('app', 'Field'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'level_name_i' => Yii::t('app', 'Level'),
			'level_desc_i' => Yii::t('app', 'Description'),
			'archives' => Yii::t('app', 'Archives'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArchives($count=false, $publish=null)
	{
        if ($count == false) {
            $model = $this->hasMany(Archives::className(), ['level_id' => 'id'])
                ->alias('archives');
            if ($publish != null) {
                return $model->andOnCondition([sprintf('%s.publish', 'archives') => $publish]);
            } else {
                return $model->andOnCondition(['IN', sprintf('%s.publish', 'archives'), [0,1]]);
            }
		}

		$model = Archives::find()
            ->alias('t')
            ->where(['t.level_id' => $this->id]);
        if ($publish != null) {
            if ($publish == 0) {
                $model->unpublish();
            } else if ($publish == 1) {
                $model->published();
            } else if ($publish == 2) {
                $model->deleted();
            }
		} else {
            $model->andWhere(['IN', 'publish', [0,1]]);
        }
		$archives = $model->count();

		return $archives ? $archives : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'level_name']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getDescription()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'level_desc']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\query\ArchiveLevel the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\archive\models\query\ArchiveLevel(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['level_name_i'] = [
			'attribute' => 'level_name_i',
			'value' => function($model, $key, $index, $column) {
				return $model->level_name_i;
			},
		];
		$this->templateColumns['level_desc_i'] = [
			'attribute' => 'level_desc_i',
			'value' => function($model, $key, $index, $column) {
				return $model->level_desc_i;
			},
		];
		$this->templateColumns['child'] = [
			'attribute' => 'child',
			'value' => function($model, $key, $index, $column) {
				return $model::getChild($model->child, ',');
			},
			'filter' => false,
			'format' => 'html',
		];
		$this->templateColumns['field'] = [
			'attribute' => 'field',
			'value' => function($model, $key, $index, $column) {
				return $model::getField($model->field, ',');
			},
			'filter' => false,
			'format' => 'html',
		];
		$this->templateColumns['archives'] = [
			'attribute' => 'archives',
			'value' => function($model, $key, $index, $column) {
				$archives = $model->getArchives(true);
				return Html::a($archives, ['admin/manage', 'level'=>$model->primaryKey, 'data'=>'yes'], ['title'=>Yii::t('app', '{count} archives', ['count'=>$archives]), 'data-pjax'=>0]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * function getLevel
	 */
	public static function getLevel($publish=null, $array=true)
	{
		$model = self::find()->alias('t')
			->select(['t.id', 't.level_name']);
		$model->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.level_name=title.id');
        if ($publish != null) {
            $model->andWhere(['t.publish' => $publish]);
        }

		$model = $model->orderBy('t.orders ASC')->all();

        if ($array == true) {
            return \yii\helpers\ArrayHelper::map($model, 'id', 'level_name_i');
        }

		return $model;
	}

	/**
	 * function getChild
	 */
	public static function getChild($child, $sep='li')
	{
        if (!is_array($child) || (is_array($child) && empty($child))) {
            return '-';
        }

		$levels = self::getLevel();
		foreach ($levels as $key => $val) {
            if (in_array($key, $child)) {
                $level[$key] = $val;
            }
		}

        if ($sep == 'li') {
			return Html::ul($level, ['item' => function($item, $index) {
				return Html::tag('li', Html::a($item, ['setting/level/view', 'id'=>$index], ['title'=>$item, 'class'=>'modal-btn']));
			}, 'class'=>'list-boxed']);
		}

		return implode(', ', $level);
	}

	/**
	 * function getField
	 */
	public static function getField($field=null, $sep='li')
	{
		$items = array(
            'creator' => Yii::t('app', 'Name of creator(s)'),
            'repository' => Yii::t('app', 'Repository'),
            'archive_date' => Yii::t('app', 'Archive Date'),
            'archive_type' => Yii::t('app', 'Archive Type'),
            'archive_file' => Yii::t('app', 'Archive File'),
            'media' => Yii::t('app', 'Media Type'),
            'subject' => Yii::t('app', 'Subject'),
            'function' => Yii::t('app', 'Function'),
            'location' => Yii::t('app', 'Location'),
            'medium' => Yii::t('app', 'Extent and medium'),
            'sidkkas' => Yii::t('app', 'SiDKKAS'),
		);

        if ($field !== null) {
            if (!is_array($field) || (is_array($field) && empty($field))) {
                return '-';
            }

			$item = [];
			foreach ($items as $key => $val) {
                if (in_array($key, $field)) {
                    $item[$key] = $val;
                }
			}

            if ($sep == 'li') {
				return Html::ul($item, ['item' => function($item, $index) {
					return Html::tag('li', "($index) $item");
				}, 'class'=>'list-boxed']);
			}

			return implode(', ', $item);
		} else
			return $items;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->level_name_i = isset($this->title) ? $this->title->message : '';
		$this->level_desc_i = isset($this->description) ? $this->description->message : '';
		$this->child = unserialize($this->child);
		$this->field = unserialize($this->field);
        if (!is_array($this->field)) {
            $this->field = [];
        }
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                if ($this->creation_id == null) {
                    $this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            } else {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
        $module = strtolower(Yii::$app->controller->module->id);
        $controller = strtolower(Yii::$app->controller->id);
        $action = strtolower(Yii::$app->controller->action->id);

        $location = Inflector::slug($module.' '.$controller);

        if (parent::beforeSave($insert)) {
            if ($insert || (!$insert && !$this->level_name)) {
                $level_name = new SourceMessage();
                $level_name->location = $location.'_title';
                $level_name->message = $this->level_name_i;
                if ($level_name->save()) {
                    $this->level_name = $level_name->id;
                }

            } else {
                $level_name = SourceMessage::findOne($this->level_name);
                $level_name->message = $this->level_name_i;
                $level_name->save();
            }

            if ($insert || (!$insert && !$this->level_desc)) {
                $level_desc = new SourceMessage();
                $level_desc->location = $location.'_description';
                $level_desc->message = $this->level_desc_i;
                if ($level_desc->save()) {
                    $this->level_desc = $level_desc->id;
                }

            } else {
                $level_desc = SourceMessage::findOne($this->level_desc);
                $level_desc->message = $this->level_desc_i;
                $level_desc->save();
            }

            $this->child = serialize($this->child);
            $this->field = serialize($this->field);
        }
        return true;
	}
}
