<?php
/**
 * m221007_224623_archive_module_createTable_archiveFavourites
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 7 October 2022, 22:47 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\db\Schema;

class m221007_224623_archive_module_createTable_archiveFavourites extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_favourites}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'archive_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'creation_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_archive_favourites_ibfk_1 FOREIGN KEY ([[archive_id]]) REFERENCES {{%ommu_archives}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

			$this->createIndex(
				'publicWithArchive',
				$tableName,
				['publish', 'archive_id']
			);

			$this->createIndex(
				'archiveWithUser',
				$tableName,
				['archive_id', 'user_id']
			);

			$this->createIndex(
				'user_id',
				$tableName,
				['user_id']
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_favourites}}';
		$this->dropTable($tableName);
	}
}
