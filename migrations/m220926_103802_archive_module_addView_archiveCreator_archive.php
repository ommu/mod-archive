<?php
/**
 * m220926_103802_archive_module_addView_archiveCreator_archive
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 25 Septemmber 2022, 21:28 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use Yii;
use yii\db\Schema;

class m220926_103802_archive_module_addView_archiveCreator_archive extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_creator_statistic_archive`');
		$this->execute('DROP VIEW IF EXISTS `_archive_creator`');

		// add view _archive_creator_statistic_archive
		$addViewArchiveCreatorStatisticArchive = <<< SQL
CREATE VIEW `_archive_creator_statistic_archive` AS
select
  `a`.`creator_id` AS `id`,
  count(`a`.`id`) AS `archives`
from `ommu_archive_related_creator` `a`
group by `a`.`creator_id`;
SQL;
		$this->execute($addViewArchiveCreatorStatisticArchive);

		// add view _archive_creator
		$addViewArchiveCreator = <<< SQL
CREATE VIEW `_archive_creator` AS
select
  `a`.`id`       AS `id`,
  `b`.`archives` AS `archives`
from (`ommu_archive_creator` `a`
   left join `_archive_creator_statistic_archive` `b`
     on (`b`.`id` = `a`.`id`));
SQL;
		$this->execute($addViewArchiveCreator);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_creator_statistic_archive`');
		$this->execute('DROP VIEW IF EXISTS `_archive_creator`');
    }
}
