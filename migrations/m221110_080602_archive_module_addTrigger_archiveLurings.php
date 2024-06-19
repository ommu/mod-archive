<?php
/**
 * m221110_080602_archive_module_addTrigger_archiveLurings
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 26 November 2022, 08:06 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use yii\db\Schema;

class m221110_080602_archive_module_addTrigger_archiveLurings extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterDeleteLurings`');

        // create trigger archiveAfterDeleteLurings
        $archiveAfterDeleteLurings = <<< SQL
CREATE
    TRIGGER `archiveAfterDeleteLurings` AFTER DELETE ON `ommu_archive_lurings` 
    FOR EACH ROW BEGIN
	IF (OLD.publish <> 2) THEN
		UPDATE `ommu_archive_grid` SET `luring` = `luring` - 1 WHERE `id` = OLD.archive_id;
	END IF;
    END;
SQL;
        $this->execute($archiveAfterDeleteLurings);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterDeleteLurings`');
    }
}
