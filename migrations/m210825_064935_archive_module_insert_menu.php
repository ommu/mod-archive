<?php
/**
 * m210825_064935_archive_module_insert_menu
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 25 August 2020, 06:50 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use Yii;
use mdm\admin\components\Configs;
use app\models\Menu;

class m210825_064935_archive_module_insert_menu extends \yii\db\Migration
{
	public function up()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;

        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['SIKS', 'archive', 'fa-archive', null, '/#', null, null],
			]);

			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Inventory', 'archive', null, Menu::getParentId('SIKS#archive'), '/archive/admin/index', null, null],
				['Settings', 'archive', null, Menu::getParentId('SIKS#archive'), '/archive/setting/admin/index', null, null],
				['Fonds', 'archive', null, Menu::getParentId('SIKS#archive'), '/archive/fond/index', null, null],
			]);
		}
	}
}
