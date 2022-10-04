<?php
/**
 * m220926_060629_archive_module_addTrigger_level
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 26 September 2022, 06:09 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use Yii;
use yii\db\Schema;

class m220926_060629_archive_module_addTrigger_level extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertLevel`');

		// alter trigger archiveAfterInsertLevel
		$archiveAfterInsertLevel = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertLevel` AFTER INSERT ON `ommu_archive_level` 
    FOR EACH ROW BEGIN
	INSERT `ommu_archive_level_grid` (`id`, `archive`) 
	VALUE (NEW.id, 0);
    END;
SQL;
		$this->execute($archiveAfterInsertLevel);
	}

	public function down()
	{
		$this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertLevel`');
	}
}
