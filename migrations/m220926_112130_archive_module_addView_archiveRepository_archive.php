<?php
/**
 * m220926_112130_archive_module_addView_archiveRepository_archive
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

class m220926_112130_archive_module_addView_archiveRepository_archive extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_repository_statistic_archive`');
		$this->execute('DROP VIEW IF EXISTS `_archive_repository`');

		// add view _archive_repository_statistic_archive
		$addViewArchiveRepositoryStatisticArchive = <<< SQL
CREATE VIEW `_archive_repository_statistic_archive` AS
select
  `a`.`repository_id` AS `id`,
  count(`a`.`id`) AS `archives`
from `ommu_archive_related_repository` `a`
group by `a`.`repository_id`;
SQL;
		$this->execute($addViewArchiveRepositoryStatisticArchive);

		// add view _archive_repository
		$addViewArchiveRepository = <<< SQL
CREATE VIEW `_archive_repository` AS
select
  `a`.`id`       AS `id`,
  `b`.`archives` AS `archives`
from (`ommu_archive_repository` `a`
   left join `_archive_repository_statistic_archive` `b`
     on (`b`.`id` = `a`.`id`));
SQL;
		$this->execute($addViewArchiveRepository);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_repository_statistic_archive`');
		$this->execute('DROP VIEW IF EXISTS `_archive_repository`');
    }
}
