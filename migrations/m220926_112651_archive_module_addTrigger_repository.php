<?php
/**
 * m220926_112651_archive_module_addTrigger_repository
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

class m220926_112651_archive_module_addTrigger_repository extends \yii\db\Migration
{
	public function up()
	{
		$this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertRepository`');

		// alter trigger archiveAfterInsertRepository
		$archiveAfterInsertRepository = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertRepository` AFTER INSERT ON `ommu_archive_repository` 
    FOR EACH ROW BEGIN
	INSERT `ommu_archive_repository_grid` (`id`, `archive`) 
	VALUE (NEW.id, 0);
    END;
SQL;
		$this->execute($archiveAfterInsertRepository);
	}

	public function down()
	{
		$this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertRepository`');
	}
}
