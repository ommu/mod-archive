<?php
/**
 * m230104_154601_archive_module_addMenu_syncFondId
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 30 January 2023, 06:59 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use yii\base\InvalidConfigException;
use yii\rbac\DbManager;
use mdm\admin\components\Configs;
use app\models\Menu;

class m230104_154601_archive_module_addMenu_syncFondId extends \yii\db\Migration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }

	/**
	 * {@inheritdoc}
	 */
    protected function getMigrationTable()
    {
        return \app\commands\MigrateController::getMigrationTable();
    }

	public function up()
	{
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;
        $schema = $this->db->getSchema()->defaultSchema;

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'type', 'data', 'created_at'], [
				['/archive/sync/admin/index', '2', '', time()],
				['/archive/sync/admin/fond', '2', '', time()],
				['/archive/sync/admin/set-fond', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
				['archiveModLevelModerator', '/archive/sync/admin/index'],
				['archiveModLevelModerator', '/archive/sync/admin/fond'],
				['archiveModLevelModerator', '/archive/sync/admin/set-fond'],
			]);
		}

        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;

        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Synchronization', 'archive', null, Menu::getParentId('Settings#archive'), '/archive/sync/admin/index', 6, null],
			]);
        }
	}

	public function down()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;

        if (Yii::$app->db->getTableSchema($tableName, true)) {
            $this->delete($tableName, ['name' => 'Synchronization', 'route' => '/archive/sync/admin/index', 'module' => 'archive']);
        }
	}
}
