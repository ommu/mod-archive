<?php
/**
 * m230724_012722_archiveModule_insertRole_archiveRestoration
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 01:28 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\base\InvalidConfigException;
use yii\rbac\DbManager;

class m230724_012722_archiveModule_insertRole_archiveRestoration extends \yii\db\Migration
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
				['/archive/restoration/admin/*', '2', '', time()],
				['/archive/restoration/admin/index', '2', '', time()],
				['/archive/restoration/history/*', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['parent', 'child'], [
				['archiveModLevelModerator', '/archive/restoration/admin/*'],
				['archiveModLevelModerator', '/archive/restoration/admin/index'],
				['archiveModLevelModerator', '/archive/restoration/history/*'],
			]);
		}
	}
}
