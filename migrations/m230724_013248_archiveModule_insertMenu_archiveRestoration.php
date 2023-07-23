<?php
/**
 * m230724_013248_archiveModule_insertMenu_archiveRestoration
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 01:33 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use Yii;
use mdm\admin\components\Configs;
use app\models\Menu;

class m230724_013248_archiveModule_insertMenu_archiveRestoration extends \yii\db\Migration
{
	public function up()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;

        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Restoration', 'archive', null, Menu::getParentId('SIKS (Layanan)#archive'), '/archive/restoration/admin/index', null, null],
			]);
		}
	}
}
