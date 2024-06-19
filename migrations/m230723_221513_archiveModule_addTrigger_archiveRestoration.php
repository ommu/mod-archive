<?php
/**
 * m230723_221513_archiveModule_addTrigger_archiveRestoration
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 23 July 2023, 22:15 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use yii\db\Schema;

class m230723_221513_archiveModule_addTrigger_archiveRestoration extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeInsertRestoration`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertRestoration`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateRestoration`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdateRestoration`');

        // create trigger archiveBeforeInsertRestoration
        $archiveBeforeInsertRestoration = <<< SQL
CREATE
    TRIGGER `archiveBeforeInsertRestoration` BEFORE INSERT ON `ommu_archive_restoration` 
    FOR EACH ROW BEGIN
	SET NEW.condition_date = NEW.creation_date;
    END;
SQL;
        $this->execute($archiveBeforeInsertRestoration);

        // create trigger archiveAfterInsertRestoration
        $archiveAfterInsertRestoration = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertRestoration` AFTER INSERT ON `ommu_archive_restoration` 
    FOR EACH ROW BEGIN
	INSERT `ommu_archive_restoration_history` (`id`, `restoration_id`, `condition`, `condition_date`, `creation_id`) 
	VALUE (uuid(), NEW.id, NEW.condition, NEW.condition_date, NEW.creation_id);
    END;
SQL;
        $this->execute($archiveAfterInsertRestoration);

        // create trigger archiveBeforeUpdateRestoration
        $archiveBeforeUpdateRestoration = <<< SQL
CREATE
    TRIGGER `archiveBeforeUpdateRestoration` BEFORE UPDATE ON `ommu_archive_restoration` 
    FOR EACH ROW BEGIN
	IF (NEW.condition <> OLD.condition) THEN
		SET NEW.condition_date = NEW.modified_date;
	END IF;
    END;
SQL;
        $this->execute($archiveBeforeUpdateRestoration);

        // create trigger archiveAfterUpdateRestoration
        $archiveAfterUpdateRestoration = <<< SQL
CREATE
    TRIGGER `archiveAfterUpdateRestoration` AFTER UPDATE ON `ommu_archive_restoration` 
    FOR EACH ROW BEGIN
	IF (NEW.condition <> OLD.condition AND NEW.condition_date <> OLD.condition_date) THEN
		INSERT `ommu_archive_restoration_history` (`id`, `restoration_id`, `condition`, `condition_date`, `creation_id`) 
		VALUE (UUID(), NEW.id, NEW.condition, NEW.condition_date, NEW.modified_id);
	END IF;
    END;
SQL;
        $this->execute($archiveAfterUpdateRestoration);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeInsertRestoration`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertRestoration`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateRestoration`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdateRestoration`');

    }
}
