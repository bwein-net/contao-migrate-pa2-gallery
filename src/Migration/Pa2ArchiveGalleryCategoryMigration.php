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

class Pa2ArchiveGalleryCategoryMigration implements MigrationInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function shouldRun(): bool
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (null === $schemaManager || !$schemaManager->tablesExist(['tl_photoalbums2_archive', 'tl_bwein_gallery_category'])) {
            return false;
        }

        $query = 'SELECT true FROM tl_photoalbums2_archive LIMIT 1';

        if (!(bool) $this->connection->executeQuery($query)->fetchOne()) {
            return false;
        }

        $query = 'SELECT true FROM tl_bwein_gallery_category LIMIT 1';

        return !(bool) $this->connection->executeQuery($query)->fetchOne();
    }

    public function run(): MigrationResult
    {
        $statement = $this->connection->prepare('SELECT * FROM tl_photoalbums2_archive');
        $result = $statement->executeQuery();
        $rows = $result->fetchAllAssociative();

        foreach ($rows as $row) {
            $statementInsert = $this->connection->prepare(
                'INSERT INTO `tl_bwein_gallery_category` (
                    `id`,
                    `tstamp`,
                    `title`,
                    `jumpTo`,
                    `protected`,
                    `groups`
                ) VALUES (
                    :id,
                    :tstamp,
                    :title,
                    :jumpTo,
                    :protected,
                    :groups
                 )',
            );

            $statementInsert->executeStatement(
                [
                    'id' => $row['id'],
                    'tstamp' => $row['tstamp'],
                    'title' => $row['title'],
                    'jumpTo' => 0,
                    'protected' => (int) $row['protected'],
                    'groups' => $row['groups'],
                ],
            );
        }

        return new MigrationResult(true, $this->getName().' successful');
    }

    public function getName(): string
    {
        return 'Photoalbums2 Archive to Gallery Category Migration';
    }
}
