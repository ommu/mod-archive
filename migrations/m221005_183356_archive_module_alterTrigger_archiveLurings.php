<?php
/**
 * m221005_183356_archive_module_alterTrigger_archiveLurings
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

class m221005_183356_archive_module_alterTrigger_archiveLurings extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertLurings`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdateLurings`');

        // create trigger archiveAfterInsertLurings
        $archiveAfterInsertLurings = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertLurings`AFTER INSERT ON `ommu_archive_lurings` 
    FOR EACH ROW BEGIN
	INSERT `ommu_archive_luring_grid` (`id`, `download`) 
	VALUE (NEW.id, 0);

	UPDATE `ommu_archive_grid` SET `luring` = `luring` + 1 WHERE `id` = NEW.archive_id;
    END;
SQL;
        $this->execute($archiveAfterInsertLurings);

        // create trigger archiveAfterUpdateLurings
        $archiveAfterUpdateLurings = <<< SQL
CREATE
    TRIGGER `archiveAfterUpdateLurings`AFTER UPDATE ON `ommu_archive_lurings` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish AND NEW.publish = 2) THEN
		UPDATE `ommu_archive_grid` SET `luring` = `luring` - 1 WHERE `id` = NEW.archive_id;
	END IF;
    END;
SQL;
        $this->execute($archiveAfterUpdateLurings);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertLurings`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdateLurings`');

        // create trigger archiveAfterInsertLurings
        $archiveAfterInsertLurings = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertLurings`AFTER INSERT ON `ommu_archive_lurings` 
    FOR EACH ROW BEGIN
	INSERT `ommu_archive_luring_grid` (`id`, `download`) 
	VALUE (NEW.id, 0);
    END;
SQL;
        $this->execute($archiveAfterInsertLurings);
    }
}
