<?php
/**
 * m220926_104624_archive_module_addTrigger_creator
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

class m220926_104624_archive_module_addTrigger_creator extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertCreator`');

		// alter trigger archiveAfterInsertCreator
		$archiveAfterInsertCreator = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertCreator` AFTER INSERT ON `ommu_archive_creator` 
    FOR EACH ROW BEGIN
	INSERT `ommu_archive_creator_grid` (`id`, `archive`) 
	VALUE (NEW.id, 0);
    END;
SQL;
		$this->execute($archiveAfterInsertCreator);
	}

	public function down()
	{
		$this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertCreator`');
	}
}
