<?php
/**
 * ArchiveViewHistory
 *
 * This is the ActiveQuery class for [[\ommu\archive\models\ArchiveViewHistory]].
 * @see \ommu\archive\models\ArchiveViewHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.co)
 * @created date 12 Fabruary 2020, 08:42 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\query;

class ArchiveViewHistory extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveViewHistory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveViewHistory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
