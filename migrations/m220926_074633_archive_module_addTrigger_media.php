<?php
/**
 * m220926_074633_archive_module_addTrigger_media
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 26 September 2022, 06:09 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use yii\db\Schema;

class m220926_074633_archive_module_addTrigger_media extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertMedia`');

		// alter trigger archiveAfterInsertMedia
		$archiveAfterInsertMedia = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertMedia` AFTER INSERT ON `ommu_archive_media` 
    FOR EACH ROW BEGIN
	INSERT `ommu_archive_media_grid` (`id`, `archive`) 
	VALUE (NEW.id, 0);
    END;
SQL;
		$this->execute($archiveAfterInsertMedia);
	}

	public function down()
	{
		$this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertMedia`');
	}
}
