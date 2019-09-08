<?php
/**
 * ArchiveRepository
 *
 * This is the ActiveQuery class for [[\ommu\archive\models\ArchiveRepository]].
 * @see \ommu\archive\models\ArchiveRepository
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 3 April 2019, 16:19 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\query;

class ArchiveRepository extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 */
	public function published() 
	{
		return $this->andWhere(['t.publish' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish() 
	{
		return $this->andWhere(['t.publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleted() 
	{
		return $this->andWhere(['t.publish' => 2]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function suggest() 
	{
		return $this->select(['id', 'repository_name'])
			->published();
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveRepository[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveRepository|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
