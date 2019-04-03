<?php
/**
 * ArchiveRelatedRepository
 *
 * This is the ActiveQuery class for [[\ommu\archive\models\ArchiveRelatedRepository]].
 * @see \ommu\archive\models\ArchiveRelatedRepository
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 4 April 2019, 06:21 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\query;

class ArchiveRelatedRepository extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveRelatedRepository[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveRelatedRepository|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
