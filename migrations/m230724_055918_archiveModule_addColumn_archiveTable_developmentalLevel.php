<?php
/**
 * m230724_055918_archiveModule_addColumn_archiveTable_developmentalLevel
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 06:00 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

class m230724_055918_archiveModule_addColumn_archiveTable_developmentalLevel extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archives';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'developmental_level',
				$this->string(32)
                    ->notNull()
                    ->after('archive_file'),
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archives';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->dropColumn(
				$tableName,
				'developmental_level',
			);
		}
	}
}
