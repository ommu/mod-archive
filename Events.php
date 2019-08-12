<?php
/**
 * Events class
 *
 * Menangani event-event yang ada pada modul archive.
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 5 April 2019, 06:42 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

namespace ommu\archive;

use Yii;
use yii\helpers\Inflector;
use ommu\archive\models\ArchiveCreator;
use ommu\archive\models\ArchiveRepository;
use ommu\archive\models\ArchiveRelatedMedia;
use ommu\archive\models\ArchiveRelatedCreator;
use ommu\archive\models\ArchiveRelatedRepository;
use ommu\archive\models\ArchiveRelatedSubject;
use ommu\core\models\CoreTags;
use ommu\archive\models\ArchiveRoomStorage;

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
		self::setArchiveSubject($archive);
		self::setArchiveSubject($archive, 'function');
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
				ArchiveRelatedMedia::find()
					->select(['id'])
					->andWhere(['id' => $key])
					->one()
					->delete();
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
					->andWhere(['archive_id'=>$archive->id, 'creator_id'=>$key])
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
		$oldRepository = array_flip($archive->getRelatedRepository(true));

		if((empty($oldRepository) && !$archive->repository) || in_array($archive->repository, $oldRepository))
			return;
		
		if(is_numeric($archive->repository)) {
			if(empty($oldRepository)) {
				$model = new ArchiveRelatedRepository();
				$model->archive_id = $archive->id;
				$model->repository_id = $archive->repository;
				$model->save();
			} else {
				$model = ArchiveRelatedRepository::findOne(key($oldRepository));
				$model->repository_id = $archive->repository;
				$model->save();
			}

		} else {
			if($archive->repository) {
				$repositoryFind = ArchiveRepository::find()
					->select(['id'])
					->andWhere(['repository_name' => $archive->repository])
					->one();

				if($repositoryFind != null)
					$repository_id = $repositoryFind->id;
				else {
					$model = new ArchiveRepository();
					$model->repository_name = $archive->repository;
					if($model->save())
						$repository_id = $model->id;
				}

				$model = new ArchiveRelatedRepository();
				$model->archive_id = $archive->id;
				$model->repository_id = $repository_id;
				$model->save();
			} else {
				// drop old repository
				ArchiveRelatedRepository::find()
					->select(['id'])
					->andWhere(['id'=>key($oldRepository)])
					->one()
					->delete();
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public static function setArchiveSubject($archive, $type='subject')
	{
		if($type == 'subject') {
			$oldSubject = $archive->getRelatedSubject(true, 'title');
			if($archive->subject)
				$subject = explode(',', $archive->subject);
		} else {
			$oldSubject = $archive->getRelatedFunction(true, 'title');
			if($archive->function)
				$subject = explode(',', $archive->function);
		}

		// insert difference subject
		if(is_array($subject)) {
			foreach ($subject as $val) {
				if(in_array($val, $oldSubject)) {
					unset($oldSubject[array_keys($oldSubject, $val)[0]]);
					continue;
				}

				$subjectFind = CoreTags::find()
					->select(['tag_id'])
					->andWhere(['body' => Inflector::slug($val)])
					->one();

				if($subjectFind != null)
					$tag_id = $subjectFind->tag_id;
				else {
					$model = new CoreTags();
					$model->body = $val;
					if($model->save())
						$tag_id = $model->tag_id;
				}

				$model = new ArchiveRelatedSubject();
				$model->type = $type;
				$model->archive_id = $archive->id;
				$model->tag_id = $tag_id;
				$model->save();
			}
		}

		// drop difference subject
		if(!empty($oldSubject)) {
			foreach ($oldSubject as $key => $val) {
				ArchiveRelatedSubject::find()
					->select(['id'])
					->where(['type'=>$type, 'archive_id'=>$archive->id, 'tag_id'=>$key])
					->one()
					->delete();
			}
		}
	}
	/**
	 * {@inheritdoc}
	 */
	public static function onBeforeSaveArchiveLocation($event)
	{
		$location = $event->sender;

		$oldStorage = array_flip($location->getRoomStorage(true));
		$storage = $location->storage;

		// insert difference storage
		if(is_array($storage)) {
			foreach ($storage as $val) {
				if(in_array($val, $oldStorage)) {
					unset($oldStorage[array_keys($oldStorage, $val)[0]]);
					continue;
				}

				$model = new ArchiveRoomStorage();
				$model->room_id = $location->id;
				$model->storage_id = $val;
				$model->save();
			}
		}

		// drop difference storage
		if(!empty($oldStorage)) {
			foreach ($oldStorage as $key => $val) {
				ArchiveRoomStorage::find()
					->select(['id'])
					->andWhere(['id' => $key])
					->one()
					->delete();
			}
		}
		
	}
}