<?php
/**
 * m221011_054038_archive_module_changeMenu_siks
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 30 August 2021, 06:59 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use mdm\admin\components\Configs;
use app\models\Menu;

class m221011_054038_archive_module_changeMenu_siks extends \yii\db\Migration
{
	/**
	 * {@inheritdoc}
	 */
    protected function getMigrationTable()
    {
        return \app\commands\MigrateController::getMigrationTable();
    }

	public function up()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;

        if (Yii::$app->db->getTableSchema($tableName, true)) {
            $this->update($tableName, ['name' => 'SIKS (Layanan)'], ['name' => 'SIKS', 'route' => '/#']);
        }
	}

	public function down()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;

        if (Yii::$app->db->getTableSchema($tableName, true)) {
            $this->update($tableName, ['name' => 'SIKS'], ['name' => 'SIKS (Layanan)', 'route' => '/#']);
        }
	}
}
