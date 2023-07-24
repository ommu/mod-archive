<?php
/**
 * m221005_081829_archive_module_insertRole_senaraiLuring
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 5 October 2022, 08:19 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m221005_081829_archive_module_insertRole_senaraiLuring extends \yii\db\Migration
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
				['/archive/luring/admin/*', '2', '', time()],
				['/archive/luring/admin/index', '2', '', time()],
				['/archive/luring/download/*', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
				['archiveModLevelModerator', '/archive/luring/admin/*'],
				['archiveModLevelModerator', '/archive/luring/admin/index'],
				['archiveModLevelModerator', '/archive/luring/download/*'],
			]);
		}
	}
}
