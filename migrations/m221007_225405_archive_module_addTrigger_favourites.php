<?php
/**
 * m221007_225405_archive_module_addTrigger_favourites
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 26 Septemmber 2022, 05:48 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use yii\db\Schema;

class m221007_225405_archive_module_addTrigger_favourites extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertFavourites`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateFavourites`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdateFavourites`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeInsertFavouriteHistory`');

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

        // create trigger archiveBeforeUpdateFavourites
        $archiveBeforeUpdateFavourites = <<< SQL
CREATE
    TRIGGER `archiveBeforeUpdateFavourites` BEFORE UPDATE ON `ommu_archive_favourites` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute($archiveBeforeUpdateFavourites);

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

        // create trigger archiveBeforeInsertFavouriteHistory
        $archiveBeforeInsertFavouriteHistory = <<< SQL
CREATE
    TRIGGER `archiveBeforeInsertFavouriteHistory` BEFORE INSERT ON `ommu_archive_favourite_history` 
    FOR EACH ROW BEGIN
	SET NEW.id = UUID();
    END;
SQL;
        $this->execute($archiveBeforeInsertFavouriteHistory);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertFavourites`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateFavourites`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdateFavourites`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeInsertFavouriteHistory`');
    }
}
