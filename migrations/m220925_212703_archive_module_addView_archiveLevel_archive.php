<?php
/**
 * m220925_212703_archive_module_addView_archiveLevel_archive
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 25 Septemmber 2022, 21:28 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use yii\db\Schema;

class m220925_212703_archive_module_addView_archiveLevel_archive extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_level_statistic_archive`');
		$this->execute('DROP VIEW IF EXISTS `_archive_level`');

		// add view _archive_level_statistic_archive
		$addViewArchiveLevelStatisticArchive = <<< SQL
CREATE VIEW `_archive_level_statistic_archive` AS
select
  `a`.`level_id` AS `id`,
  count(`a`.`id`) AS `archives`
from `ommu_archives` `a`
where `a`.`publish` <> 2
group by `a`.`level_id`;
SQL;
		$this->execute($addViewArchiveLevelStatisticArchive);

		// add view _archive_level
		$addViewArchiveLevel = <<< SQL
CREATE VIEW `_archive_level` AS
select
  `a`.`id`       AS `id`,
  `b`.`archives` AS `archives`
from (`ommu_archive_level` `a`
   left join `_archive_level_statistic_archive` `b`
     on (`b`.`id` = `a`.`id`));
SQL;
		$this->execute($addViewArchiveLevel);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_level_statistic_archive`');
		$this->execute('DROP VIEW IF EXISTS `_archive_level`');
    }
}
