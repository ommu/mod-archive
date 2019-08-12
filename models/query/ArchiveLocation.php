<?php
/**
 * ArchiveLocation
 *
 * This is the ActiveQuery class for [[\ommu\archive\models\ArchiveLocation]].
 * @see \ommu\archive\models\ArchiveLocation
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 April 2019, 08:37 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\query;

class ArchiveLocation extends \yii\db\ActiveQuery
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
		return $this->andWhere(['publish' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish() 
	{
		return $this->andWhere(['publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleted() 
	{
		return $this->andWhere(['publish' => 2]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveLocation[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 */
	public function suggest() 
	{
		return $this->select(['id', 'location_name'])
			->published();
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveLocation|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
