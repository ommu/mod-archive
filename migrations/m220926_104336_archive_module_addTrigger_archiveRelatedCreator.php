<?php
/**
 * m220926_104336_archive_module_addTrigger_archiveRelatedCreator
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 26 Septemmber 2022, 05:48 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use Yii;
use yii\db\Schema;

class m220926_104336_archive_module_addTrigger_archiveRelatedCreator extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertRelatedCreator`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterDeleteRelatedCreator`');

        // create trigger archiveAfterInsertRelatedCreator
        $archiveAfterInsertRelatedCreator = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertRelatedCreator` AFTER INSERT ON `ommu_archive_related_creator` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_creator_grid` SET `archive` = `archive` + 1 WHERE `id` = NEW.creator_id;
    END;
SQL;
        $this->execute($archiveAfterInsertRelatedCreator);

        // create trigger archiveAfterDeleteRelatedCreator
        $archiveAfterDeleteRelatedCreator = <<< SQL
CREATE
    TRIGGER `archiveAfterDeleteRelatedCreator` AFTER DELETE ON `ommu_archive_related_creator` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_creator_grid` SET `archive` = `archive` - 1 WHERE `id` = OLD.creator_id;
    END;
SQL;
        $this->execute($archiveAfterDeleteRelatedCreator);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertRelatedCreator`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterDeleteRelatedCreator`');
    }
}
