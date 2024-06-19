<?php
/**
 * m230724_004737_archiveModule_updateTrigger_archiveRestoration_archiveRestorationStatus
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 00:48 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use yii\db\Schema;

class m230724_004737_archiveModule_updateTrigger_archiveRestoration_archiveRestorationStatus extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertRestoration`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdateRestoration`');

        // create trigger archiveAfterInsertRestoration
        $archiveAfterInsertRestoration = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertRestoration` AFTER INSERT ON `ommu_archive_restoration` 
    FOR EACH ROW BEGIN
	INSERT `ommu_archive_restoration_history` (`id`, `restoration_id`, `condition`, `condition_date`, `creation_id`) 
	VALUE (uuid(), NEW.id, NEW.condition, NEW.condition_date, NEW.creation_id);

	UPDATE `ommu_archives` SET `restoration_status` = NEW.condition WHERE `id` = NEW.archive_id;
    END;
SQL;
        $this->execute($archiveAfterInsertRestoration);

        // create trigger archiveAfterUpdateRestoration
        $archiveAfterUpdateRestoration = <<< SQL
CREATE
    TRIGGER `archiveAfterUpdateRestoration` AFTER UPDATE ON `ommu_archive_restoration` 
    FOR EACH ROW BEGIN
	IF (NEW.condition <> OLD.condition AND NEW.condition_date <> OLD.condition_date) THEN
		INSERT `ommu_archive_restoration_history` (`id`, `restoration_id`, `condition`, `condition_date`, `creation_id`) 
		VALUE (UUID(), NEW.id, NEW.condition, NEW.condition_date, NEW.modified_id);

		UPDATE `ommu_archives` SET `restoration_status` = NEW.condition WHERE `id` = NEW.archive_id;
	END IF;
    END;
SQL;
        $this->execute($archiveAfterUpdateRestoration);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertRestoration`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdateRestoration`');

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
}
