<?php
/**
 * ArchiveRelatedMedia
 *
 * This is the ActiveQuery class for [[\ommu\archive\models\ArchiveRelatedMedia]].
 * @see \ommu\archive\models\ArchiveRelatedMedia
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 5 March 2019, 23:34 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\query;

class ArchiveRelatedMedia extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveRelatedMedia[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveRelatedMedia|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
