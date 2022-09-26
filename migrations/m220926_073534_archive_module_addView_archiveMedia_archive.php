<?php
/**
 * m220926_073534_archive_module_addView_archiveMedia_archive
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

class m220926_073534_archive_module_addView_archiveMedia_archive extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_media_statistic_archive`');

		// add view _archive_media_statistic_archive
		$addViewArchiveMediaStatisticArchive = <<< SQL
CREATE VIEW `_archive_media_statistic_archive` AS
select
  `a`.`media_id` AS `id`,
  count(`a`.`id`) AS `archives`
from `ommu_archive_related_media` `a`
group by `a`.`media_id`;
SQL;
		$this->execute($addViewArchiveMediaStatisticArchive);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_media_statistic_archive`');
    }
}
