<?php
/**
 * m221007_224813_archive_module_createTable_archiveFavouriteHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 7 October 2022, 22:48 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use Yii;
use yii\db\Schema;

class m221007_224813_archive_module_createTable_archiveFavouriteHistory extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_favourite_history}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_STRING . '(36) NOT NULL COMMENT \'trigger,uuid\'',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'favourite_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'creation_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'creation_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_archive_favourite_history_ibfk_1 FOREIGN KEY ([[favourite_id]]) REFERENCES {{%ommu_archive_favourites}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_favourite_history}}';
		$this->dropTable($tableName);
	}
}
