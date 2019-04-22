<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-24
 */

namespace Vindite\Database\Builder;

use Vindite\Database\DatabaseConstant;
use Vindite\Database\Grammar\GrammarAbstract;
use Vindite\Database\Grammar\GrammarException;
use Vindite\Database\Grammar\GrammarAwareTrait;
use Vindite\Database\Builder\Where;
use Vindite\Database\Builder\Join;
use PDOStatement;

/**
 * Responsavel por executar os statement de select
 */
final class Update extends GrammarAbstract
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
        $this->checkFields();
        $this->checkTable();
        $where = $this->getWhere();
        $join  = $this->getJoin();

        $bindParams = \array_merge(
            $this->getBindUpdateValues(),
            $where->getWherePayload()
        );

        $stringSql = \str_replace(
            [
                '__TABLENAME__',
                '__JOIN__',
                '__FIELDS__',
                '__WHERE__'
            ],
            [
                $this->table,
                $join->getParsedString(),
                $this->makeSetClause(),
                $where->getParsedString()
            ],
            $this->grammar[DatabaseConstant::UPDATE]
        );

        if (empty($stringSql)) {
            throw new GrammarException("Erro ao parser os dados da query");
        }

        $stmt = $this->transaction->open()->prepare($stringSql);
        if (!$stmt->execute($bindParams)) {
            throw new GrammarException("Erro ao executar o statement");
        }

        return $stmt;
    }

    /**
     * Cria a clausula do set usada no update
     *
     * @return string
     */
    private function makeSetClause() : string
    {
        $setClauses = \array_combine($this->fields, $this->bindParam);

        $setString = '';
        foreach ($setClauses as $key => $value) {
            $setString .= "{$key} = {$value}";
            if (next($setClauses)) {
                $setString .= ", ";
            }
        }

        return $setString;
    }

    /**
     * Retorna o arrau com os dados do combine entre os valores e o bind do update
     *
     * @return array
     */
    private function getBindUpdateValues() : array
    {
        return \array_combine($this->bindParam, $this->values);
    }
}
