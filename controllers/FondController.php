<?php
/**
 * FondController
 * @var $this ommu\archive\controllers\FondController
 * @var $model ommu\archive\models\Archives
 *
 * FondController implements the CRUD actions for Archives model.
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
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 6 March 2019, 09:19 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive\controllers;

use Yii;
use ommu\archive\controllers\AdminController;

class FondController extends AdminController
{
	/**
	 * {@inheritdoc}
	 */
	public function getViewPath()
	{
		return $this->module->getViewPath() . DIRECTORY_SEPARATOR . 'admin';
	}
}
