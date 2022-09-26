<?php
/**
 * m220926_074132_archive_module_addTrigger_archiveRelatedMedia
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

class m220926_074132_archive_module_addTrigger_archiveRelatedMedia extends \yii\db\Migration
{
	public function up()
	{
       $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertRelatedMedia`');
       $this->execute('DROP TRIGGER IF EXISTS `archiveAfterDeleteRelatedMedia`');

        // create trigger archiveAfterInsertRelatedMedia
        $archiveAfterInsertRelatedMedia = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertRelatedMedia`AFTER INSERT ON `ommu_archive_related_media` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_media_grid` SET `archive` = `archive` + 1 WHERE `id` = NEW.media_id;
    END;
SQL;
        $this->execute($archiveAfterInsertRelatedMedia);

        // create trigger archiveAfterDeleteRelatedMedia
        $archiveAfterDeleteRelatedMedia = <<< SQL
CREATE
    TRIGGER `archiveAfterDeleteRelatedMedia` AFTER DELETE ON `ommu_archive_related_media` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_media_grid` SET `archive` = `archive` - 1 WHERE `id` = OLD.media_id;
    END;
SQL;
        $this->execute($archiveAfterDeleteRelatedMedia);
	}

	public function down()
	{
       $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertRelatedMedia`');
       $this->execute('DROP TRIGGER IF EXISTS `archiveAfterDeleteRelatedMedia`');
    }
}
