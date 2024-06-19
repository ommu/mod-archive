<?php
/**
 * m221025_214413_archive_module_changeMenu_setting
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

class m221025_214413_archive_module_changeMenu_setting extends \yii\db\Migration
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
            $this->update($tableName, ['route' => '/#'], ['name' => 'Settings', 'route' => '/archive/setting/admin/index']);

			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Archive Settings', 'archive', null, Menu::getParentId('Settings#archive'), '/archive/setting/admin/index', 6, null],
				['Levels of Descriptions', 'archive', null, Menu::getParentId('Settings#archive'), '/archive/setting/level/index', 1, null],
				['Media Types', 'archive', null, Menu::getParentId('Settings#archive'), '/archive/setting/media/index', 2, null],
				['Creators', 'archive', null, Menu::getParentId('Settings#archive'), '/archive/setting/creator/index', 3, null],
				['Repositories', 'archive', null, Menu::getParentId('Settings#archive'), '/archive/setting/repository/index', 4, null],
				['Physical Storage', 'archive', null, Menu::getParentId('Settings#archive'), '/archive-location/admin/index', 5, null],
			]);

            $this->delete($tableName, ['name' => 'Physical Storage', 'route' => '/archive-location/admin/index', 'module' => 'archive-location']);
        }
	}

	public function down()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;

        if (Yii::$app->db->getTableSchema($tableName, true)) {
            $this->update($tableName, ['route' => '/archive/setting/admin/index'], ['name' => 'Settings', 'route' => '/#']);

            $this->delete($tableName, ['name' => 'Archive Settings', 'route' => '/archive/setting/admin/index', 'module' => 'archive']);
            $this->delete($tableName, ['name' => 'Levels of Descriptions', 'route' => '/archive/setting/level/index', 'module' => 'archive']);
            $this->delete($tableName, ['name' => 'Media Types', 'route' => '/archive/setting/media/index', 'module' => 'archive']);
            $this->delete($tableName, ['name' => 'Creators', 'route' => '/archive/setting/creator/index', 'module' => 'archive']);
            $this->delete($tableName, ['name' => 'Repositories', 'route' => '/archive/setting/repository/index', 'module' => 'archive']);
            $this->delete($tableName, ['name' => 'Physical Storage', 'route' => '/archive-location/admin/index', 'module' => 'archive']);
        }
	}
}
