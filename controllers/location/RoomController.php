<?php
/**
 * AdminController
 * @var $this app\components\View
 * @var $model ommu\archive\models\ArchiveLocation
 *
 * AdminController implements the CRUD actions for ArchiveLocation model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 April 2019, 08:42 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\controllers\location;

use Yii;
use ommu\archive\controllers\location\AdminController;
use ommu\archive\models\ArchiveLocation;

class RoomController extends AdminController
{
	/**
	 * {@inheritdoc}
	 */
	public function getType()
	{
		return ArchiveLocation::TYPE_ROOM;
	}
}
