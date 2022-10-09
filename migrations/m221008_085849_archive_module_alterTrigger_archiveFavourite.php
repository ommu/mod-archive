<?php
/**
 * m221008_085849_archive_module_alterTrigger_archiveFavourite
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

class m221008_085849_archive_module_alterTrigger_archiveFavourite extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertFavourites`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdateFavourites`');

        // create trigger archiveAfterInsertFavourites
        $archiveAfterInsertFavourites = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertFavourites` AFTER INSERT ON `ommu_archive_favourites` 
    FOR EACH ROW BEGIN
	INSERT `ommu_archive_favourite_history` (`favourite_id`, `publish`, `creation_ip`, `creation_date`)
	VALUE (NEW.id, NEW.publish, NEW.creation_ip, NEW.creation_date);

	UPDATE `ommu_archive_grid` SET `favourite` = `favourite` + 1 WHERE `id` = NEW.archive_id;
    END;
SQL;
        $this->execute($archiveAfterInsertFavourites);

        // create trigger archiveAfterUpdateFavourites
        $archiveAfterUpdateFavourites = <<< SQL
CREATE
    TRIGGER `archiveAfterUpdateFavourites` AFTER UPDATE ON `ommu_archive_favourites` 
    FOR EACH ROW BEGIN
	IF (NEW.updated_date <> OLD.updated_date) THEN
		INSERT `ommu_archive_favourite_history` (`favourite_id`, `publish`, `creation_ip`, `creation_date`)
		VALUE (NEW.id, NEW.publish, NEW.creation_ip, NEW.creation_date);

		IF (NEW.publish = 1) THEN
			UPDATE `ommu_archive_grid` SET `favourite` = `favourite` + 1 WHERE `id` = NEW.archive_id;
		ELSE
			UPDATE `ommu_archive_grid` SET `favourite` = `favourite` - 1 WHERE `id` = NEW.archive_id;
		END IF;
	END IF;
    END;
SQL;
        $this->execute($archiveAfterUpdateFavourites);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertFavourites`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdateFavourites`');

        // create trigger archiveAfterInsertFavourites
        $archiveAfterInsertFavourites = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertFavourites` AFTER INSERT ON `ommu_archive_favourites` 
    FOR EACH ROW BEGIN
	INSERT `ommu_archive_favourite_history` (`favourite_id`, `publish`, `creation_ip`, `creation_date`)
	VALUE (NEW.id, NEW.publish, NEW.creation_ip, NEW.creation_date);
    END;
SQL;
        $this->execute($archiveAfterInsertFavourites);

        // create trigger archiveAfterUpdateFavourites
        $archiveAfterUpdateFavourites = <<< SQL
CREATE
    TRIGGER `archiveAfterUpdateFavourites` AFTER UPDATE ON `ommu_archive_favourites` 
    FOR EACH ROW BEGIN
	IF (NEW.updated_date <> OLD.updated_date) THEN
		INSERT `ommu_archive_favourite_history` (`favourite_id`, `publish`, `creation_ip`, `creation_date`)
		VALUE (NEW.id, NEW.publish, NEW.creation_ip, NEW.creation_date);
	END IF;
    END;
SQL;
        $this->execute($archiveAfterUpdateFavourites);
    }
}
