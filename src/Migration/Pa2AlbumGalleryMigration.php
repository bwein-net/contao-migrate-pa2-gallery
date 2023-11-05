<?php

declare(strict_types=1);

/*
 * This file is part of migration of photoalbums2 to gallery for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

namespace Bwein\MigratePa2Gallery\Migration;

use Contao\CoreBundle\Migration\MigrationInterface;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;

class Pa2AlbumGalleryMigration implements MigrationInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function shouldRun(): bool
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (null === $schemaManager || !$schemaManager->tablesExist(['tl_photoalbums2_album', 'tl_bwein_gallery'])) {
            return false;
        }

        $query = 'SELECT true FROM tl_photoalbums2_album LIMIT 1';

        if (!(bool) $this->connection->executeQuery($query)->fetchOne()) {
            return false;
        }

        $query = 'SELECT true FROM tl_bwein_gallery LIMIT 1';

        return !(bool) $this->connection->executeQuery($query)->fetchOne();
    }

    public function run(): MigrationResult
    {
        $statement = $this->connection->executeQuery('SELECT * FROM tl_photoalbums2_album');

        while (false !== ($row = $statement->fetchAssociative())) {
            $statementInsert = $this->connection->prepare(
                'INSERT INTO `tl_bwein_gallery` (
                    `id`,
                    `featured`,
                    `pid`,
                    `tstamp`,
                    `title`,
                    `alias`,
                    `author`,
                    `startDate`,
                    `endDate`,
                    `images`,
                    `sortBy`,
                    `previewImageType`,
                    `previewImage`,
                    `event`,
                    `place`,
                    `photographer`,
                    `description`,
                    `cssClass`,
                    `start`,
                    `stop`,
                    `published`
                ) VALUES (
                    :id,
                    :featured,
                    :pid,
                    :tstamp,
                    :title,
                    :alias,
                    :author,
                    :startDate,
                    :endDate,
                    :images,
                    :sortBy,
                    :previewImageType,
                    :previewImage,
                    :event,
                    :place,
                    :photographer,
                    :description,
                    :cssClass,
                    :start,
                    :stop,
                    :published
                )',
            );

            $statementInsert->executeStatement(
                [
                    'id' => $row['id'],
                    'featured' => 0,
                    'pid' => $row['pid'],
                    'tstamp' => $row['tstamp'],
                    'title' => $row['title'],
                    'alias' => $row['alias'],
                    'author' => $row['author'],
                    'startDate' => !empty($row['startdate']) ? (int) $row['startdate'] : null,
                    'endDate' => !empty($row['enddate']) ? (int) $row['enddate'] : null,
                    'images' => $row['images'],
                    'sortBy' => $this->mapSortBy($row['imageSortType']),
                    'previewImageType' => $row['previewImageType'],
                    'previewImage' => $row['previewImage'],
                    'event' => $this->getTranslationsField((int) $row['event']),
                    'place' => $this->getTranslationsField((int) $row['place']),
                    'photographer' => $this->getTranslationsField((int) $row['photographer']),
                    'description' => $this->getTranslationsField((int) $row['description']),
                    'cssClass' => $row['cssClass'],
                    'start' => $row['start'],
                    'stop' => $row['stop'],
                    'published' => (int) $row['published'],
                ],
            );
        }

        return new MigrationResult(true, $this->getName().' successful');
    }

    public function getName(): string
    {
        return 'Photoalbums2 Album to Gallery Migration';
    }

    private function mapSortBy($sortType): string
    {
        switch ($sortType) {
            case 'name_asc':
            case 'name_desc':
            case 'date_asc':
            case 'date_desc':
            case 'random':
            case 'custom':
                return $sortType;
            case 'metatitle_asc':
                return 'name_asc';
            case 'metatitle_desc':
                return 'name_desc';
            default:
                return '';
        }
    }

    private function getTranslationsField(int $fieldId): string
    {
        if (0 === $fieldId) {
            return '';
        }

        $schemaManager = $this->connection->createSchemaManager();

        if (null === $schemaManager || !$schemaManager->tablesExist(['tl_translation_fields'])) {
            return '';
        }
        $statement = $this->connection->executeQuery(
            'SELECT content FROM tl_translation_fields WHERE fid = :fieldId',
            ['fieldId' => $fieldId],
        );
        $content = $statement->fetchFirstColumn();

        return implode('; ', $content);
    }
}
