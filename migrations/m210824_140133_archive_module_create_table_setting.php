<?php
/**
 * m210824_140133_archive_module_create_table_setting
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 24 August 2021, 14:04 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use Yii;
use yii\db\Schema;

class m210824_140133_archive_module_create_table_setting extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_setting';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_TINYINT . '(1) UNSIGNED NOT NULL AUTO_INCREMENT',
				'license' => Schema::TYPE_STRING . '(32) NOT NULL',
				'permission' => Schema::TYPE_TINYINT . '(1) NOT NULL',
				'meta_description' => Schema::TYPE_TEXT . ' NOT NULL',
				'meta_keyword' => Schema::TYPE_TEXT . ' NOT NULL',
				'fond_sidkkas' => Schema::TYPE_TINYINT . '(1) NOT NULL COMMENT \'"0=disable, 1=enable"\'',
				'reference_code_sikn' => Schema::TYPE_STRING . '(32) NOT NULL',
				'reference_code_separator' => Schema::TYPE_STRING . '(4) NOT NULL',
				'short_code' => Schema::TYPE_TINYINT . '(1) NOT NULL COMMENT \'Enable,Disable\'',
				'medium_sublevel' => Schema::TYPE_TINYINT . '(1) NOT NULL',
				'production_date' => Schema::TYPE_DATE . ' NOT NULL DEFAULT \'0000-00-00\'',
				'image_type' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'json\'',
				'document_type' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'json\'',
				'maintenance_mode' => Schema::TYPE_TINYINT . '(1) NOT NULL COMMENT \'"0=disable, 1=enable"\'',
				'maintenance_image_path' => Schema::TYPE_STRING . '(32) NOT NULL',
				'maintenance_document_path' => Schema::TYPE_STRING . '(32) NOT NULL',
				'breadcrumb_param' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'json\'',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'PRIMARY KEY ([[id]])',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_setting';
		$this->dropTable($tableName);
	}
}
