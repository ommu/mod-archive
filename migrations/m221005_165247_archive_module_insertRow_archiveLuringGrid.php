<?php
/**
 * m221005_165247_archive_module_insertRow_archiveLuringGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 25 Septemmber 2022, 21:33 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use yii\db\Schema;

class m221005_165247_archive_module_insertRow_archiveLuringGrid extends \yii\db\Migration
{
	public function up()
	{
		$insertRowArchiveLuringGrid = <<< SQL
INSERT INTO `ommu_archive_luring_grid` (`id`, `download`) 

SELECT 
	a.id AS id,
	case when a.downloads is null then 0 else a.downloads end AS `downloads`
FROM _archive_lurings AS a
LEFT JOIN ommu_archive_luring_grid AS b
	ON b.id = a.id
WHERE
	b.id IS NULL;
SQL;
		$this->execute($insertRowArchiveLuringGrid);
	}
}
