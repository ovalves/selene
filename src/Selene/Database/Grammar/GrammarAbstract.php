<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-23
 */

namespace Selene\Database\Grammar;

use PDOStatement;
use Selene\Database\Builder\Group;
use Selene\Database\Builder\Insert;
use Selene\Database\Builder\Join;
use Selene\Database\Builder\Update;
use Selene\Database\Builder\Where;
use Selene\Database\DatabaseException;
use Selene\Database\Transaction;

/**
 * Responsavel por disponibilizar os métodos comuns entre as classes de builder de query.
 */
abstract class GrammarAbstract
{
    /**
     * Guarda a transação ativa.
     *
     * @var Transaction
     */
    protected $transaction;

    /**
     * Guarda os campos a serem usados na clausula.
     *
     * @var mixed
     */
    protected $fields;

    /**
     * Guarda os valores a serem usados na clausula.
     *
     * @var mixed
     */
    protected $values;

    /**
     * Guarda os valores a serem usados como bind na clausula.
     *
     * @var mixed
     */
    protected $bindParam;

    /**
     * Guarda a tabela a ser usada na clausula.
     *
     * @var mixed
     */
    protected $table;

    /**
     * Guarda os dados que serão usados na clausula where.
     *
     * @var array
     */
    protected $where = [];

    /**
     * Guarda os dados que serão usados na clausula order.
     *
     * @var array
     */
    protected $order = [];

    /**
     * Guarda o valor que será usado no offset na montagem da query.
     *
     * @var int
     */
    protected $offset = 0;

    /**
     * Guarda o valor que será usado no limit na montagem da query.
     *
     * @var int
     */
    protected $limit = 15;

    /**
     * Guarda os dados que serão usados na clausula join.
     *
     * @var array
     */
    protected $join = [];

    /**
     * Guarda os dados que serão usados na clausula group.
     *
     * @var string
     */
    protected $group = '';

    /**
     * Constructor.
     */
    final public function __construct(Transaction $transaction, $fields = null)
    {
        $this->transaction = $transaction;
        if (!empty($fields)) {
            $this->fields = $fields;
        }
    }

    /**
     * Cria uma claususa where.
     */
    final public function where(array $where): self
    {
        $this->where[] = $where;

        return $this;
    }

    /**
     * Cria uma claususa order by.
     */
    final public function order(string $column, string $sort = 'ASC'): self
    {
        $this->order[] = \addslashes($column) . ' ' . \addslashes($sort);

        return $this;
    }

    /**
     * Cria uma claususa offset.
     */
    final public function offset(int $offset): self
    {
        $this->offset = (int) ($offset < 1) ? 0 : $offset;

        return $this;
    }

    /**
     * Cria uma claususa limit.
     */
    final public function limit(int $limit): self
    {
        $this->limit = (int) ($limit < 1) ? 1 : $limit;

        return $this;
    }

    /**
     * Seta a tabela corrente.
     */
    final public function table(string $table): self
    {
        $this->table = \addslashes($table);

        return $this;
    }

    /**
     * Cria uma claususa Left Join.
     */
    final public function leftJoin(string $table, string $column, string $operator, string $onClause): self
    {
        $this->join(
            $table,
            $column,
            $operator,
            $onClause,
            'LEFT'
        );

        return $this;
    }

    /**
     * Cria uma claususa Inner Join.
     */
    final public function join(string $table, string $column, string $operator, string $onClause, $type = 'INNER'): self
    {
        $table = $this->escape($table);
        $column = $this->escape($column);
        $operator = $this->escape($operator);
        $onClause = $this->escape($onClause);

        $this->join[] = "{$type} JOIN {$table} ON {$column} {$operator} {$onClause}";

        return $this;
    }

    /**
     * Retorna os campos de pesquisa da query.
     */
    final public function getFields(): string
    {
        if (is_array($this->fields)) {
            return implode(', ', $this->fields);
        }

        if (is_string($this->fields)) {
            return $this->fields;
        }

        throw new DatabaseException('Error Processing Request', 1);
    }

    /**
     * Retorna os dados da clausula where.
     */
    final protected function getWhere(): Where
    {
        return new Where($this->where);
    }

    /**
     * Retorna os dados da clausula order.
     */
    final protected function getOrder(): string
    {
        if (empty($this->order)) {
            return '';
        }

        $orderString = 'ORDER BY ';
        $orderCount = count($this->order) - 1;
        for ($i = 0; $i <= $orderCount; $i++) {
            if ($i >= $orderCount) {
                $orderString .= "{$this->order[$i]}";
                continue;
            }

            $orderString .= "{$this->order[$i]}, ";
        }

        return $orderString;
    }

    /**
     * Retorna o valor do limit que será usado na query.
     */
    final protected function getLimit(): string
    {
        return "LIMIT {$this->limit}";
    }

    /**
     * Retorna o valor do offset que será usado na query.
     */
    final protected function getOffset(): string
    {
        return "OFFSET {$this->offset}";
    }

    /**
     * Retorna os dados da clausula join.
     */
    final protected function getJoin(): Join
    {
        return new Join($this->join);
    }

    /**
     * Retorna os dados da clausula group.
     */
    final protected function getGroup(): Group
    {
        return new Group($this->group);
    }

    /**
     * Checa se os campos estão formatados corretamente.
     */
    final public function checkFields(): bool
    {
        if (empty($this->fields)) {
            throw new DatabaseException('Erro ao processar a query os campos não podem ser vazios');
        }

        if (\is_array($this->fields)) {
            $this->fields = $this->prepare($this->fields);

            return true;
        }

        $this->fields = $this->escape($this->fields);

        return true;
    }

    /**
     * Checa se a tabela esta preenchida corretamente.
     */
    final public function checkTable(): void
    {
        if (empty($this->table)) {
            throw new DatabaseException('Tabela não encontrada', 500);
        }
    }

    /**
     * Itera pelos elemantos do statement
     * Executa o escape da query.
     */
    protected function prepare(array $fields): array
    {
        $prepared = [];
        if ($this instanceof Insert || $this instanceof Update) {
            foreach ($fields as $key => $value) {
                if (\is_scalar($value)) {
                    $this->bindParam[] = \str_pad($key, \strlen($key) + 1, ':', STR_PAD_LEFT);
                    $this->values[] = $this->escape($value);
                    $prepared[$key] = $this->escape($key);
                }
            }

            $this->fields = \array_keys($prepared);

            return $this->fields;
        }

        foreach ($fields as $field) {
            if (\is_scalar($field)) {
                $prepared[] = $this->escape($field);
                continue;
            }

            $prepared[] = $field;
        }

        return $prepared;
    }

    /**
     * Executa o escape da query.
     *
     * @param mixed $value
     */
    protected function escape($value)
    {
        if (!\is_scalar($value)) {
            return 'null';
        }

        if (\is_string($value) and (!empty($value))) {
            return \addslashes($value);
        }

        if (\is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ('' !== $value) {
            return $value;
        }

        return 'null';
    }

    /**
     * Executa a query.
     *
     * @param array $whereClause
     */
    abstract public function execute(): PDOStatement;
}
