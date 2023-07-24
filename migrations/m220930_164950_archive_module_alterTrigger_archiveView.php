<?php
/**
 * m220930_164950_archive_module_alterTrigger_archiveView
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 Septemmber 2022, 07:43 WIB
 * @link https://github.com/ommu/mod-archive
 *
 */

use yii\db\Schema;

class m220930_164950_archive_module_alterTrigger_archiveView extends \yii\db\Migration
{
	public function up()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsert`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertViews`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdateViews`');

        // create trigger archiveAfterInsert
        $archiveAfterInsert = <<< SQL
CREATE
    TRIGGER `archiveAfterInsert` AFTER INSERT ON `ommu_archives` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_level_grid` SET `archive` = `archive` + 1 WHERE `id` = NEW.level_id;

	INSERT `ommu_archive_grid` (`id`, `view`) 
	VALUE (NEW.id, 0);
    END;
SQL;
        $this->execute($archiveAfterInsert);

        // create trigger archiveAfterInsertViews
        $archiveAfterInsertViews = <<< SQL
CREATE
    TRIGGER `archiveAfterInsertViews` AFTER INSERT ON `ommu_archive_views` 
    FOR EACH ROW BEGIN
	IF (NEW.publish = 1 AND NEW.views <> 0) THEN
		INSERT `ommu_archive_view_history` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.id, NEW.view_date, NEW.view_ip);

		UPDATE `ommu_archive_grid` SET `view` = `view` + 1 WHERE `id` = NEW.id;
	END IF;
    END;
SQL;
        $this->execute($archiveAfterInsertViews);

        // create trigger archiveAfterUpdateViews
        $archiveAfterUpdateViews = <<< SQL
CREATE
    TRIGGER `archiveAfterUpdateViews` AFTER UPDATE ON `ommu_archive_views` 
    FOR EACH ROW BEGIN
	IF (NEW.view_date <> OLD.view_date) THEN
		INSERT `ommu_archive_view_history` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.id, NEW.view_date, NEW.view_ip);

		UPDATE `ommu_archive_grid` SET `view` = `view` + 1 WHERE `id` = NEW.id;
	END IF;
    END;
SQL;
        $this->execute($archiveAfterUpdateViews);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsert`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterInsertViews`');
        $this->execute('DROP TRIGGER IF EXISTS `archiveAfterUpdateViews`');

       // create trigger archiveAfterInsert
        $archiveAfterInsert = <<< SQL
CREATE
    TRIGGER `archiveAfterInsert` AFTER INSERT ON `ommu_archives` 
    FOR EACH ROW BEGIN
	UPDATE `ommu_archive_level_grid` SET `archive` = `archive` + 1 WHERE `id` = NEW.level_id;
    END;
SQL;
        $this->execute($archiveAfterInsert);

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
}
