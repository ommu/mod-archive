<?php
/**
 * m220926_073807_archive_module_insertRow_archiveMediaGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 25 Septemmber 2022, 21:33 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use Yii;
use yii\db\Schema;

class m220926_073807_archive_module_insertRow_archiveMediaGrid extends \yii\db\Migration
{
	public function up()
	{
		$insertRowArchiveMediaGrid = <<< SQL
INSERT INTO `ommu_archive_media_grid` (`id`, `archive`) 

SELECT 
	a.id AS id,
	case when a.archives is null then 0 else a.archives end AS `archives`
FROM _archive_media_statistic_archive AS a
LEFT JOIN ommu_archive_media_grid AS b
	ON b.id = a.id
WHERE
	b.id IS NULL;
SQL;
		$this->execute($insertRowArchiveMediaGrid);
	}
}
