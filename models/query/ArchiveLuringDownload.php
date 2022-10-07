<?php
/**
 * ArchiveLuringDownload
 *
 * This is the ActiveQuery class for [[\ommu\archive\models\ArchiveLuringDownload]].
 * @see \ommu\archive\models\ArchiveLuringDownload
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 October 2022, 20:27 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\models\query;

class ArchiveLuringDownload extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveLuringDownload[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\archive\models\ArchiveLuringDownload|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
