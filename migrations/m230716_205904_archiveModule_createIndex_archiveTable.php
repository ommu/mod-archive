<?php
/**
 * m230716_205904_archiveModule_createIndex_archiveTable
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 16 July 2023, 20:59 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

class m230716_205904_archiveModule_createIndex_archiveTable extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archives';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createIndex(
				'parent_id',
				$tableName,
				['parent_id'],
			);
		}
	}
}
