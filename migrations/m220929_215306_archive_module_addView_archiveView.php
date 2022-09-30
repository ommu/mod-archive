<?php
/**
 * m220929_215306_archive_module_addView_archiveView
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

class m220929_215306_archive_module_addView_archiveView extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_statistic_view`');
		$this->execute('DROP VIEW IF EXISTS `_archives`');

		// add view _archive_statistic_view
		$addViewArchiveStatisticView = <<< SQL
CREATE VIEW `_archive_statistic_view` AS
select
  `a`.`id` AS `id`,
  sum(`a`.`views`) AS `views`
from `ommu_archive_views` `a`
group by `a`.`id`;
SQL;
		$this->execute($addViewArchiveStatisticView);

		// add view _archives
		$addViewArchives = <<< SQL
CREATE VIEW `_archives` AS
select
  `a`.`id`    AS `id`,
  `b`.`views` AS `views`
from (`ommu_archives` `a`
   left join `_archive_statistic_view` `b`
     on (`a`.`id` = `b`.`id`));
SQL;
		$this->execute($addViewArchives);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_statistic_view`');
		$this->execute('DROP VIEW IF EXISTS `_archives`');
    }
}
