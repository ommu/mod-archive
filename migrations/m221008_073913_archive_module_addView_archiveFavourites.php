<?php
/**
 * m221008_073913_archive_module_addView_archiveFavourites
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 29 Septemmber 2022, 21:57 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use yii\db\Schema;

class m221008_073913_archive_module_addView_archiveFavourites extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_statistic_favourite`');
		$this->execute('DROP VIEW IF EXISTS `_archives`');

		// add view _archive_statistic_favourite
		$addViewArchiveStatisticFavourite = <<< SQL
CREATE VIEW `_archive_statistic_favourite` AS
select
  `a`.`archive_id` AS `archive_id`,
  count(`a`.`id`)  AS `favourites`
from `ommu_archive_favourites` `a`
where `a`.`publish` = 1
group by `a`.`archive_id`;
SQL;
		$this->execute($addViewArchiveStatisticFavourite);

		// add view _archives
		$addViewArchives = <<< SQL
CREATE VIEW `_archives` AS
select
  `a`.`id`         AS `id`,
  `b`.`views`      AS `views`,
  `c`.`lurings`    AS `lurings`,
  `d`.`favourites` AS `favourites`
from (((`ommu_archives` `a`
     left join `_archive_statistic_view` `b`
       on (`a`.`id` = `b`.`archive_id`))
    left join `_archive_statistic_luring` `c`
      on (`a`.`id` = `c`.`archive_id`))
   left join `_archive_statistic_favourite` `d`
     on (`a`.`id` = `d`.`archive_id`));
SQL;
		$this->execute($addViewArchives);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_archive_statistic_favourite`');
		$this->execute('DROP VIEW IF EXISTS `_archives`');

		// add view _archives
		$addViewArchives = <<< SQL
CREATE VIEW `_archives` AS
select
  `a`.`id`      AS `id`,
  `b`.`views`   AS `views`,
  `c`.`lurings` AS `lurings`
from ((`ommu_archives` `a`
    left join `_archive_statistic_view` `b`
      on (`a`.`id` = `b`.`archive_id`))
   left join `_archive_statistic_favourite` `c`
     on (`a`.`id` = `c`.`archive_id`));
SQL;
		$this->execute($addViewArchives);
    }
}
