<?php
/**
 * m220926_112501_archive_module_addTrigger_archiveRelatedRepository
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

class m220926_112501_archive_module_addTrigger_archiveRelatedRepository extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertRelatedRepository`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterDeleteRelatedRepository`');

        // create trigger archiveAfterInsertRelatedRepository
        $archiveAfterInsertRelatedRepository = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertRelatedRepository`AFTER INSERT ON `ommu_archive_related_repository` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_repository_grid` SET `archive` = `archive` + 1 WHERE `id` = NEW.repository_id;
    END;
SQL;
        $this->execute($archiveAfterInsertRelatedRepository);

        // create trigger archiveAfterDeleteRelatedRepository
        $archiveAfterDeleteRelatedRepository = <<< SQL
CREATE
    TRIGGER `archiveAfterDeleteRelatedRepository` AFTER DELETE ON `ommu_archive_related_repository` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_repository_grid` SET `archive` = `archive` - 1 WHERE `id` = OLD.repository_id;
    END;
SQL;
        $this->execute($archiveAfterDeleteRelatedRepository);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertRelatedRepository`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterDeleteRelatedRepository`');
    }
}
