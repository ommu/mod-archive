<?php
/**
 * m210824_140626_archive_module_create_table_archives
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 24 August 2021, 14:06 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use Yii;
use yii\db\Schema;

class m210824_140626_archive_module_create_table_archives extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archives';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'sidkkas' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'parent_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'level_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL',
				'title' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'redactor\'',
				'code' => Schema::TYPE_STRING . '(255) NOT NULL',
				'medium' => Schema::TYPE_TEXT . ' NOT NULL',
				'archive_type' => Schema::TYPE_STRING,
				'archive_date' => Schema::TYPE_STRING . '(64) NOT NULL',
				'archive_file' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'file\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_archives_ibfk_1 FOREIGN KEY ([[level_id]]) REFERENCES ommu_archive_level ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

			$this->createIndex(
				'publishWithParent',
				$tableName,
				['publish', 'parent_id']
			);

			$this->createIndex(
				'publishWithLevel',
				$tableName,
				['publish', 'level_id']
			);

			$this->createIndex(
				'parent_id',
				$tableName,
				['parent_id']
			);

			$this->createIndex(
				'idWithPublish',
				$tableName,
				['id', 'publish']
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archives';
		$this->dropTable($tableName);
	}
}
