<?php
/**
 * Events class
 *
 * Menangani event-event yang ada pada modul archive.
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 5 April 2019, 06:42 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive;

use Yii;
use ommu\archive\models\ArchiveCreator;
use ommu\archive\models\ArchiveRepository;
use ommu\archive\models\ArchiveRelatedMedia;
use ommu\archive\models\ArchiveRelatedCreator;
use ommu\archive\models\ArchiveRelatedRepository;

class Events extends \yii\base\BaseObject
{
	/**
	 * {@inheritdoc}
	 */
	public static function onBeforeSaveArchives($event)
	{
		$archive = $event->sender;

		self::setArchiveMedia($archive);
		self::setArchiveCreator($archive);
		self::setArchiveRepository($archive);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setArchiveMedia($archive)
	{
		$oldMedia = array_flip($archive->getRelatedMedia(true));
		$media = $archive->media;

		// insert difference media
		if(is_array($media)) {
			foreach ($media as $val) {
				if(in_array($val, $oldMedia)) {
					unset($oldMedia[array_keys($oldMedia, $val)[0]]);
					continue;
				}

				$model = new ArchiveRelatedMedia();
				$model->archive_id = $archive->id;
				$model->media_id = $val;
				$model->save();
			}
		}

		// drop difference media
		if(!empty($oldMedia)) {
			foreach ($oldMedia as $key => $val) {
				ArchiveRelatedMedia::findOne($key)->delete();
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setArchiveCreator($archive)
	{
		$oldCreator = $archive->getRelatedCreator(true, 'title');
		if($archive->creator)
			$creator = explode(',', $archive->creator);

		// insert difference creator
		if(is_array($creator)) {
			foreach ($creator as $val) {
				if(in_array($val, $oldCreator)) {
					unset($oldCreator[array_keys($oldCreator, $val)[0]]);
					continue;
				}

				$creatorFind = ArchiveCreator::find()
					->select(['id'])
					->andWhere(['creator_name' => $val])
					->one();
						
				if($creatorFind != null)
					$creator_id = $creatorFind->id;
				else {
					$model = new ArchiveCreator();
					$model->creator_name = $val;
					if($model->save())
						$creator_id = $model->id;
				}

				$model = new ArchiveRelatedCreator();
				$model->archive_id = $archive->id;
				$model->creator_id = $creator_id;
				$model->save();
			}
		}

		// drop difference creator
		if(!empty($oldCreator)) {
			foreach ($oldCreator as $key => $val) {
				ArchiveRelatedCreator::find()
					->select(['id'])
					->where(['archive_id'=>$archive->id, 'creator_id'=>$key])
					->one()
					->delete();
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setArchiveRepository($archive)
	{
		$oldRepository = $archive->getRelatedRepository(true, 'title');
		if($archive->repository)
			$repository = explode(',', $archive->repository);

		// insert difference repository
		if(is_array($repository)) {
			foreach ($repository as $val) {
				if(in_array($val, $oldRepository)) {
					unset($oldRepository[array_keys($oldRepository, $val)[0]]);
					continue;
				}

				$repositoryFind = ArchiveRepository::find()
					->select(['id'])
					->andWhere(['repository_name' => $val])
					->one();
						
				if($repositoryFind != null)
					$repository_id = $repositoryFind->id;
				else {
					$model = new ArchiveRepository();
					$model->repository_name = $val;
					if($model->save())
						$repository_id = $model->id;
				}

				$model = new ArchiveRelatedRepository();
				$model->archive_id = $archive->id;
				$model->repository_id = $repository_id;
				$model->save();
			}
		}

		// drop difference repository
		if(!empty($oldRepository)) {
			foreach ($oldRepository as $key => $val) {
				ArchiveRelatedRepository::find()
					->select(['id'])
					->where(['archive_id'=>$archive->id, 'repository_id'=>$key])
					->one()
					->delete();
			}
		}
	}
}