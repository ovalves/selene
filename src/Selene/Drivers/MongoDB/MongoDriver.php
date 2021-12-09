<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-17
 */

namespace Selene\Drivers\MongoDB;

use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;

final class MongoDriver
{
    public const QUERY_LIMIT = 15;

    private ?Manager $connection = null;
    private array $filters = [];
    private array $options = [];
    private $cursor;

    public function filters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function query(string $collection): self
    {
        $dbCollection = \env('MONGO_DATABASE') . '.' . $collection;

        $query = new Query($this->filters, $this->options);
        $this->cursor = $this->getConnection()->executeQuery($dbCollection, $query);

        return $this;
    }

    public function insert(string $collection, array $data): self
    {
        $dbCollection = \env('MONGO_DATABASE') . '.' . $collection;

        $bulk = new BulkWrite();
        $bulk->insert($data);

        $this->cursor = $this->getConnection()->executeBulkWrite($dbCollection, $bulk);

        return $this;
    }

    public function update(string $collection, array $where, array $set): self
    {
        $dbCollection = \env('MONGO_DATABASE') . '.' . $collection;

        $bulk = new BulkWrite();

        $bulk->update(
            $where,
            ['$set' => $set],
            ['multi' => false, 'upsert' => false]
        );

        $this->cursor = $this->getConnection()->executeBulkWrite($dbCollection, $bulk);

        return $this;
    }

    public function toArray(): array
    {
        return $this->cursor->toArray();
    }

    public function isValid(): bool
    {
        return (bool) (false === empty($this->toArray()));
    }

    public function getMongoCursor(): mixed
    {
        return $this->cursor;
    }

    public function getConnection(): Manager
    {
        if (null === $this->connection) {
            $connection = sprintf(
                'mongodb://%s:%s@%s:%s',
                \env('MONGO_USER'),
                \env('MONGO_PASSWORD'),
                \env('MONGO_HOST'),
                \env('MONGO_PORT'),
            );
            $this->connection = new Manager($connection);
        }

        return $this->connection;
    }
}
