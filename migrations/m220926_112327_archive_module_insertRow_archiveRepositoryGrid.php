<?php
/**
 * m220926_112327_archive_module_insertRow_archiveRepositoryGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 25 Septemmber 2022, 21:33 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use yii\db\Schema;

class m220926_112327_archive_module_insertRow_archiveRepositoryGrid extends \yii\db\Migration
{
	public function up()
	{
		$insertRowArchiveRepositoryGrid = <<< SQL
INSERT INTO `ommu_archive_repository_grid` (`id`, `archive`) 

SELECT 
	a.id AS id,
	case when a.archives is null then 0 else a.archives end AS `archives`
FROM _archive_repository AS a
LEFT JOIN ommu_archive_repository_grid AS b
	ON b.id = a.id
WHERE
	b.id IS NULL;
SQL;
		$this->execute($insertRowArchiveRepositoryGrid);
	}
}
