<?php
/**
 * m221005_165555_archive_module_addTrigger_archiveLurings
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

class m221005_165555_archive_module_addTrigger_archiveLurings extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertLurings`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateLurings`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertLuringDownload`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterDeleteLuringDownload`');

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

        // create trigger archiveBeforeUpdateLurings
        $archiveBeforeUpdateLurings = <<< SQL
CREATE
    TRIGGER `archiveBeforeUpdateLurings` BEFORE UPDATE ON `ommu_archive_lurings` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute($archiveBeforeUpdateLurings);

        // create trigger archiveAfterInsertLuringDownload
        $archiveAfterInsertLuringDownload = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertLuringDownload`AFTER INSERT ON `ommu_archive_luring_download` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_luring_grid` SET `download` = `download` + 1 WHERE `id` = NEW.luring_id;
    END;
SQL;
        $this->execute($archiveAfterInsertLuringDownload);

        // create trigger archiveAfterDeleteLuringDownload
        $archiveAfterDeleteLuringDownload = <<< SQL
CREATE
    TRIGGER `archiveAfterDeleteLuringDownload` AFTER DELETE ON `ommu_archive_luring_download` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_luring_grid` SET `download` = `download` - 1 WHERE `id` = OLD.luring_id;
    END;
SQL;
        $this->execute($archiveAfterDeleteLuringDownload);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertLurings`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateLurings`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertLuringDownload`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterDeleteLuringDownload`');
    }
}
