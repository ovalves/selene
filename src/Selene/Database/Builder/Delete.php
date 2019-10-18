<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-23
 */

namespace Selene\Database\Builder;

use Selene\Database\DatabaseConstant;
use Selene\Database\Grammar\GrammarAbstract;
use Selene\Database\Grammar\GrammarException;
use Selene\Database\Grammar\GrammarAwareTrait;
use Selene\Database\Builder\Where;
use PDOStatement;

/**
 * Responsavel por executar os statement de select
 */
final class Delete extends GrammarAbstract
{
    use GrammarAwareTrait;

    /**
     * Executa a query
     *
     * @param array $whereClause
     * @return PDOStatement
     */
    public function execute() : PDOStatement
    {
        $this->checkTable();
        $where = $this->getWhere();

        $stringSql = \str_replace(
            [
                '__TABLENAME__',
                '__WHERE__'
            ],
            [
                $this->table,
                $where->getParsedString(),
            ],
            $this->grammar[DatabaseConstant::DELETE]
        );

        if (empty($stringSql)) {
            throw new GrammarException("Erro ao parser os dados da query");
        }

        $stmt = $this->transaction->open()->prepare($stringSql);

        if (!$stmt->execute($where->getWherePayload())) {
            throw new GrammarException("Erro ao executar o statement");
        }

        return $stmt;
    }
}
