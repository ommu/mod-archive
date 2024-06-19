<?php
/**
 * ArchiveRestorationHistory
 *
 * This is the ActiveQuery class for [[\ommu\archive\models\ArchiveRestorationHistory]].
 * @see \ommu\archive\models\ArchiveRestorationHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 01:12 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\query;

class ArchiveRestorationHistory extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveRestorationHistory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveRestorationHistory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
