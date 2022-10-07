<?php
/**
 * m221005_143642_archive_module_addView_archiveLuringDownload
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 29 Septemmber 2022, 21:57 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use Yii;
use yii\db\Schema;

class m221005_143642_archive_module_addView_archiveLuringDownload extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_luring_statistic_download`');
		$this->execute('DROP VIEW IF EXISTS `_archive_lurings`');

		// add view _archive_luring_statistic_download
		$addViewArchiveLuringStatisticDownload = <<< SQL
CREATE VIEW `_archive_luring_statistic_download` AS
select
  `a`.`luring_id` AS `luring_id`,
  count(`a`.`luring_id`) AS `downloads`
from `ommu_archive_luring_download` `a`
group by `a`.`luring_id`;
SQL;
		$this->execute($addViewArchiveLuringStatisticDownload);

		// add view _archive_lurings
		$addViewArchiveLurings = <<< SQL
CREATE VIEW `_archive_lurings` AS
select
  `a`.`id`    AS `id`,
  `b`.`downloads` AS `downloads`
from (`ommu_archive_lurings` `a`
   left join `_archive_luring_statistic_download` `b`
     on (`a`.`id` = `b`.`luring_id`));
SQL;
		$this->execute($addViewArchiveLurings);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_luring_statistic_download`');
		$this->execute('DROP VIEW IF EXISTS `_archive_lurings`');
    }
}
