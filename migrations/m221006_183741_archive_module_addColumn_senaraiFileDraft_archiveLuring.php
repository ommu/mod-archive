<?php
/**
 * m221006_183741_archive_module_addColumn_senaraiFileDraft_archiveLuring
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 5 October 2022, 18:09 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\db\Schema;

class m221006_183741_archive_module_addColumn_senaraiFileDraft_archiveLuring extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_lurings';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'senarai_file_draft',
				$this->json()->notNull()->after('senarai_file'),
			);
		}
	}
}
