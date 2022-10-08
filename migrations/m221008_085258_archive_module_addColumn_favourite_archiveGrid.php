<?php
/**
 * m221008_085258_archive_module_addColumn_favourite_archiveGrid
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 5 October 2022, 18:14 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use Yii;
use yii\db\Schema;

class m221008_085258_archive_module_addColumn_favourite_archiveGrid extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_archive_grid';
		if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->addColumn(
				$tableName,
				'favourite',
				$this->integer()->notNull()->after('luring'),
			);
		}
	}
}
