<?php
/**
 * m220930_162123_archive_module_insertRow_archiveGrid_view
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 30 Septemmber 2022, 16:22 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use yii\db\Schema;

class m220930_162123_archive_module_insertRow_archiveGrid_view extends \yii\db\Migration
{
	public function up()
	{
		$insertRowArchiveGridView = <<< SQL
INSERT INTO `ommu_archive_grid` (`id`, `view`) 

SELECT 
	a.id AS id,
	case when a.views is null then 0 else a.views end AS `views`
FROM _archives AS a
LEFT JOIN ommu_archive_grid AS b
	ON b.id = a.id
WHERE
	b.id IS NULL;
SQL;
		$this->execute($insertRowArchiveGridView);
	}
}
