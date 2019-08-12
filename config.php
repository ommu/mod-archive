<?php
/**
 * archive module config
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 5 March 2019, 23:14 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use ommu\archive\Events;
use ommu\archive\models\Archives;
use ommu\archive\models\ArchiveLocation;

return [
	'id' => 'archive',
	'class' => ommu\archive\Module::className(),
	'events' => [
		[
			'class'    => Archives::className(),
			'event'    => Archives::EVENT_BEFORE_SAVE_ARCHIVES,
			'callback' => [Events::className(), 'onBeforeSaveArchives']
		],
		[
			'class'    => ArchiveLocation::className(),
			'event'    => ArchiveLocation::EVENT_BEFORE_SAVE_ARCHIVE_LOCATION,
			'callback' => [Events::className(), 'onBeforeSaveArchiveLocation']
		],
	],
];