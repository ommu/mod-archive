<?php
/**
 * m221005_180709_archive_module_addColumn_senaraiFile_archives
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 5 October 2022, 18:09 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\db\Schema;

class m221005_180709_archive_module_addColumn_senaraiFile_archives extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archives';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'senarai_file',
				$this->text()->notNull()->after('archive_file'),
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archives';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->dropColumn(
				$tableName,
				'senarai_file',
			);
		}
	}
}
