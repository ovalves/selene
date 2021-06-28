<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-23
 */

namespace Selene\Database\Builder;

use PDOStatement;
use Selene\Database\DatabaseConstant;
use Selene\Database\Grammar\GrammarAbstract;
use Selene\Database\Grammar\GrammarAwareTrait;
use Selene\Database\Grammar\GrammarException;

/**
 * Responsavel por executar os statement de select.
 */
final class Select extends GrammarAbstract
{
    use GrammarAwareTrait;

    /**
     * Executa a query.
     *
     * @param array $whereClause
     */
    public function execute(): PDOStatement
    {
        $this->checkFields();
        $this->checkTable();
        $where = $this->getWhere();

        $stringSql = \str_replace(
            [
                '__FIELDS__',
                '__TABLENAME__',
                '__JOIN__',
                '__WHERE__',
                '__ORDER__',
                '__LIMIT__',
                '__OFFSET__',
                '__GROUP__',
            ],
            [
                $this->getFields(),
                $this->table,
                $this->getJoin()->getParsedString(),
                $where->getParsedString(),
                $this->getOrder(),
                $this->getLimit(),
                $this->getOffset(),
                $this->getGroup()->getParsedString(),
            ],
            $this->grammar[DatabaseConstant::SELECT]
        );

        if (empty($stringSql)) {
            throw new GrammarException('Erro ao parser os dados da query');
        }

        $stmt = $this->transaction->open()->prepare($stringSql);

        if (!$stmt->execute($where->getWherePayload())) {
            throw new GrammarException('Erro ao executar o statement');
        }

        return $stmt;
    }
}
