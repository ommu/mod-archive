<?php
/**
 * m210824_140432_archive_module_create_table_level
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 24 August 2021, 14:05 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\db\Schema;

class m210824_140432_archive_module_create_table_level extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_level';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'level_name' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'trigger[delete]\'',
				'level_desc' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'trigger[delete],text\'',
				'child' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'serialize\'',
				'field' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'serialize\'',
				'orders' => Schema::TYPE_SMALLINT . '(5) NOT NULL',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[id]])',
			], $tableOptions);

			$this->createIndex(
				'publishWithLevelName',
				$tableName,
				['publish', 'level_name']
			);

			$this->createIndex(
				'level_name',
				$tableName,
				['level_name']
			);

			$this->createIndex(
				'level_desc',
				$tableName,
				['level_desc']
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_level';
		$this->dropTable($tableName);
	}
}
