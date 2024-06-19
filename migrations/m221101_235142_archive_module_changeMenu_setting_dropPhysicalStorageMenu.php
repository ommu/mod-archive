<?php
/**
 * m221101_235142_archive_module_changeMenu_setting_dropPhysicalStorageMenu
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 30 August 2021, 06:59 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use mdm\admin\components\Configs;
use app\models\Menu;

class m221101_235142_archive_module_changeMenu_setting_dropPhysicalStorageMenu extends \yii\db\Migration
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
            $this->delete($tableName, ['name' => 'Physical Storage', 'route' => '/archive-location/admin/index', 'module' => 'archive']);
        }
	}

	public function down()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;

        if (Yii::$app->db->getTableSchema($tableName, true)) {

			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Physical Storage', 'archive', null, Menu::getParentId('Settings#archive'), '/archive-location/admin/index', 5, null],
			]);
        }
	}
}
