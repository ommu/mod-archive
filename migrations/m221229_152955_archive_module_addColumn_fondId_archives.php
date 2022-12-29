<?php
/**
 * m221229_152955_archive_module_addColumn_fondId_archives
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 29 December 2022, 15:35 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use Yii;
use yii\db\Schema;

class m221229_152955_archive_module_addColumn_fondId_archives extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archives';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'fond_id',
				$this->integer()->after('level_id'),
			);
		}
	}
}
