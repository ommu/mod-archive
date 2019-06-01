<?php
/**
 * ArchiveRelatedLocation
 *
 * This is the ActiveQuery class for [[\ommu\archive\models\ArchiveRelatedLocation]].
 * @see \ommu\archive\models\ArchiveRelatedLocation
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 31 May 2019, 21:23 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\query;

class ArchiveRelatedLocation extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveRelatedLocation[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveRelatedLocation|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
