<?php
/**
 * m210824_141034_archive_module_create_table_related_creator
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 24 August 2021, 14:10 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use Yii;
use yii\db\Schema;

class m210824_141034_archive_module_create_table_related_creator extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_related_creator';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'archive_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'creator_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_archive_related_creator_ibfk_1 FOREIGN KEY ([[creator_id]]) REFERENCES ommu_archive_creator ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_archive_related_creator_ibfk_2 FOREIGN KEY ([[archive_id]]) REFERENCES ommu_archives ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

			$this->createIndex(
				'archiveWithCreator',
				$tableName,
				['archive_id', 'creator_id']
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_related_creator';
		$this->dropTable($tableName);
	}
}
