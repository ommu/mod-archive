<?php
/**
 * m210825_063233_archive_module_insert_role
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 25 August 2021, 06:33 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m210825_063233_archive_module_insert_role extends \yii\db\Migration
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

	public function up()
	{
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;
        $schema = $this->db->getSchema()->defaultSchema;

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'type', 'data', 'created_at'], [
				['archiveModLevelAdmin', '2', '', time()],
				['archiveModLevelModerator', '2', '', time()],
				['/archive/admin/*', '2', '', time()],
				['/archive/admin/index', '2', '', time()],
				['/archive/fond/*', '2', '', time()],
				['/archive/fond/index', '2', '', time()],
				['/archive/setting/admin/delete', '2', '', time()],
				['/archive/setting/admin/index', '2', '', time()],
				['/archive/setting/admin/update', '2', '', time()],
				['/archive/setting/creator/*', '2', '', time()],
				['/archive/setting/level/*', '2', '', time()],
				['/archive/setting/media/*', '2', '', time()],
				['/archive/setting/repository/*', '2', '', time()],
				['/archive/view/admin/*', '2', '', time()],
				['/archive/view/history/*', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
				['userAdmin', 'archiveModLevelAdmin'],
				['userModerator', 'archiveModLevelModerator'],
				['archiveModLevelAdmin', 'archiveModLevelModerator'],
				['archiveModLevelAdmin', '/archive/setting/admin/delete'],
				['archiveModLevelAdmin', '/archive/setting/admin/update'],
				['archiveModLevelAdmin', '/archive/setting/level/*'],
				['archiveModLevelModerator', '/archive/admin/*'],
				['archiveModLevelModerator', '/archive/fond/*'],
				['archiveModLevelModerator', '/archive/setting/admin/index'],
				['archiveModLevelModerator', '/archive/setting/creator/*'],
				['archiveModLevelModerator', '/archive/setting/media/*'],
				['archiveModLevelModerator', '/archive/setting/repository/*'],
				['archiveModLevelModerator', '/archive/view/admin/*'],
				['archiveModLevelModerator', '/archive/view/history/*'],
			]);
		}
	}
}
