<?php
/**
 * m230724_002046_archiveModule_addColumn_archiveTable_RestorationStatus
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 00:21 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

class m230724_002046_archiveModule_addColumn_archiveTable_RestorationStatus extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archives';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'condition',
				$this->string()
                    ->notNull()
                    ->after('archive_file'),
			);

			$this->addColumn(
				$tableName,
				'restoration_status',
				$this->string(32)
                    ->notNull()
                    ->after('condition'),
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archives';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->dropColumn(
				$tableName,
				'condition',
			);

			$this->dropColumn(
				$tableName,
				'restoration_status',
			);
		}
	}
}
