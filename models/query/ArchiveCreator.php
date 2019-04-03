<?php
/**
 * ArchiveCreator
 *
 * This is the ActiveQuery class for [[\ommu\archive\models\ArchiveCreator]].
 * @see \ommu\archive\models\ArchiveCreator
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 3 April 2019, 16:18 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\query;

class ArchiveCreator extends \yii\db\ActiveQuery
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
	 * @return \ommu\archive\models\ArchiveCreator[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveCreator|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
