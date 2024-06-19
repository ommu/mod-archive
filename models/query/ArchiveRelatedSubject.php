<?php
/**
 * ArchiveRelatedSubject
 *
 * This is the ActiveQuery class for [[\ommu\archive\models\ArchiveRelatedSubject]].
 * @see \ommu\archive\models\ArchiveRelatedSubject
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 25 May 2019, 23:45 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\query;

class ArchiveRelatedSubject extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveRelatedSubject[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveRelatedSubject|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
