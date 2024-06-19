<?php
/**
 * archive module config
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 5 March 2019, 23:14 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use ommu\archive\Events;
use ommu\archive\models\Archives;

return [
	'id' => 'archive',
	'class' => ommu\archive\Module::className(),
	'events' => [
		[
			'class'    => Archives::className(),
			'event'    => Archives::EVENT_BEFORE_SAVE_ARCHIVES,
			'callback' => [Events::className(), 'onBeforeSaveArchives']
		],
	],
];