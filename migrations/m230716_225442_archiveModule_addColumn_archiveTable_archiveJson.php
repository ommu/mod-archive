<?php
/**
 * m230716_225442_archiveModule_addColumn_archiveTable_archiveJson
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 16 July 2023, 22:55 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

class m230716_225442_archiveModule_addColumn_archiveTable_archiveJson extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archives';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'archive_json',
				$this->text()
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
				'archive_json',
			);
		}
	}
}
