<?php
/**
 * m220926_055342_archive_module_addTrigger_archive
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 26 Septemmber 2022, 05:48 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use yii\db\Schema;

class m220926_055342_archive_module_addTrigger_archive extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsert`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdate`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterDelete`');

        // create trigger archiveAfterInsert
        $archiveAfterInsert = <<< SQL
CREATE
    TRIGGER `archiveAfterInsert` AFTER INSERT ON `ommu_archives` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_level_grid` SET `archive` = `archive` + 1 WHERE `id` = NEW.level_id;
    END;
SQL;
        $this->execute($archiveAfterInsert);

        // create trigger archiveAfterUpdate
        $archiveAfterUpdate = <<< SQL
CREATE
    TRIGGER `archiveAfterUpdate` AFTER UPDATE ON `ommu_archives` 
    FOR EACH ROW BEGIN
	IF (NEW.level_id <> OLD.level_id) THEN
		UPDATE `ommu_archive_level_grid` SET `archive` = `archive` + 1 WHERE `id` = NEW.level_id;
		UPDATE `ommu_archive_level_grid` SET `archive` = `archive` - 1 WHERE `id` = OLD.level_id;
	END IF;

	IF (NEW.publish <> OLD.publish) THEN
		IF (NEW.publish = 2) THEN
			UPDATE `ommu_archive_level_grid` SET `archive` = `archive` - 1 WHERE `id` = NEW.level_id;
		ELSEIF (OLD.publish = 2) THEN
			UPDATE `ommu_archive_level_grid` SET `archive` = `archive` + 1 WHERE `id` = NEW.level_id;
		END IF;
	END IF;
    END;
SQL;
        $this->execute($archiveAfterUpdate);

        // create trigger archiveAfterDelete
        $archiveAfterDelete = <<< SQL
CREATE
    TRIGGER `archiveAfterDelete` AFTER DELETE ON `ommu_archives` 
    FOR EACH ROW BEGIN
	IF (OLD.publish <> 2) THEN
		UPDATE `ommu_archive_level_grid` SET `archive` = `archive` - 1 WHERE `id` = OLD.level_id;
	END IF;
    END;
SQL;
        $this->execute($archiveAfterDelete);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsert`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdate`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterDelete`');
    }
}
