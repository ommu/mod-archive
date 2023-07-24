<?php
/**
 * m230723_185349_archiveModule_createTable_archiveRestorationHistory
 * 
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 23 July 2023, 18:54 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\db\Schema;

class m230723_185349_archiveModule_createTable_archiveRestorationHistory extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_restoration_history';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_CHAR . '(36) NOT NULL COMMENT \'trigger, uuid\'',
				'restoration_id' => Schema::TYPE_CHAR . '(36) NOT NULL COMMENT \'uuid\'',
				'condition' => Schema::TYPE_STRING,
				'condition_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
				'creation_id' => Schema::TYPE_INTEGER . '(10) UNSIGNED',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_archive_restoration_history_ibfk_1 FOREIGN KEY ([[restoration_id]]) REFERENCES ommu_archive_restoration ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_restoration_history';
		$this->dropTable($tableName);
	}
}
