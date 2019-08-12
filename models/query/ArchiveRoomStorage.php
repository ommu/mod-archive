<?php
/**
 * ArchiveRoomStorage
 *
 * This is the ActiveQuery class for [[\ommu\archive\models\ArchiveRoomStorage]].
 * @see \ommu\archive\models\ArchiveRoomStorage
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 April 2019, 17:58 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\query;

class ArchiveRoomStorage extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveRoomStorage[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveRoomStorage|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
