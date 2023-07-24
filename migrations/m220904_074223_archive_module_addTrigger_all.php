<?php
/**
 * m220904_074223_archive_module_addTrigger_all
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 Septemmber 2022, 07:43 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use yii\db\Schema;

class m220904_074223_archive_module_addTrigger_all extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateLevel`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdate`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateRepository`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateCreator`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateMedia`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertViews`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateViews`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdateViews`');

        // create trigger archiveBeforeUpdateLevel
        $archiveBeforeUpdateLevel = <<< SQL
CREATE
    TRIGGER `archiveBeforeUpdateLevel` BEFORE UPDATE ON `ommu_archive_level` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute($archiveBeforeUpdateLevel);

        // create trigger archiveBeforeUpdate
        $archiveBeforeUpdate = <<< SQL
CREATE
    TRIGGER `archiveBeforeUpdate` BEFORE UPDATE ON `ommu_archives` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute($archiveBeforeUpdate);

        // create trigger archiveBeforeUpdateRepository
        $archiveBeforeUpdateRepository = <<< SQL
CREATE
    TRIGGER `archiveBeforeUpdateRepository` BEFORE UPDATE ON `ommu_archive_repository` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute($archiveBeforeUpdateRepository);

        // create trigger archiveBeforeUpdateCreator
        $archiveBeforeUpdateCreator = <<< SQL
CREATE
    TRIGGER `archiveBeforeUpdateCreator` BEFORE UPDATE ON `ommu_archive_creator` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute($archiveBeforeUpdateCreator);

        // create trigger archiveBeforeUpdateMedia
        $archiveBeforeUpdateMedia = <<< SQL
CREATE
    TRIGGER `archiveBeforeUpdateMedia` BEFORE UPDATE ON `ommu_archive_media` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute($archiveBeforeUpdateMedia);

        // create trigger archiveAfterInsertViews
        $archiveAfterInsertViews = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertViews` AFTER INSERT ON `ommu_archive_views` 
    FOR EACH ROW BEGIN
	IF (NEW.publish = 1 AND NEW.views <> 0) THEN
		INSERT `ommu_archive_view_history` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.id, NEW.view_date, NEW.view_ip);
	END IF;
    END;
SQL;
        $this->execute($archiveAfterInsertViews);

        // create trigger archiveBeforeUpdateViews
        $archiveBeforeUpdateViews = <<< SQL
CREATE
    TRIGGER `archiveBeforeUpdateViews` BEFORE UPDATE ON `ommu_archive_views` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	ELSE
		IF (NEW.publish = 1 AND (NEW.views <> OLD.views AND NEW.views > OLD.views)) THEN
			SET NEW.view_date = NOW();
		END IF;
	END IF;
    END;
SQL;
        $this->execute($archiveBeforeUpdateViews);

        // create trigger archiveAfterUpdateViews
        $archiveAfterUpdateViews = <<< SQL
CREATE
    TRIGGER `archiveAfterUpdateViews` AFTER UPDATE ON `ommu_archive_views` 
    FOR EACH ROW BEGIN
	IF (NEW.view_date <> OLD.view_date) THEN
		INSERT `ommu_archive_view_history` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.id, NEW.view_date, NEW.view_ip);
	END IF;
    END;
SQL;
        $this->execute($archiveAfterUpdateViews);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateLevel`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdate`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateRepository`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateCreator`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateMedia`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertViews`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveBeforeUpdateViews`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdateViews`');
    }
}
