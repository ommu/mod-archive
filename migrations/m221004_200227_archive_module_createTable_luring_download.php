<?php
/**
 * m221004_200227_archive_module_createTable_luring_download
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 October 2022, 20:02 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use Yii;
use yii\db\Schema;

class m221004_200227_archive_module_createTable_luring_download extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_luring_download}}';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_STRING . '(32) NOT NULL',
				'luring_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'download_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'download_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_archive_luring_download_ibfk_1 FOREIGN KEY ([[luring_id]]) REFERENCES {{%ommu_archive_lurings}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

			$this->createIndex(
				'user_id',
				$tableName,
				['user_id']
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . '{{%ommu_archive_luring_download}}';
		$this->dropTable($tableName);
	}
}
