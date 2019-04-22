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
use PDOStatement;

/**
 * Responsavel por executar os statement de select
 */
final class Insert extends GrammarAbstract
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

        $stringSql = \str_replace(
            [
                '__TABLENAME__',
                '__FIELDS__',
                '__VALUES__'
            ],
            [
                $this->table,
                \implode(',', $this->fields),
                \implode(',', $this->bindParam)
            ],
            $this->grammar[DatabaseConstant::INSERT]
        );

        if (empty($stringSql)) {
            throw new GrammarException("Erro ao parser os dados da query");
        }

        $stmt = $this->transaction->open()->prepare($stringSql);
        if (!$stmt->execute(\array_combine($this->bindParam, $this->values))) {
            throw new GrammarException("Erro ao executar o statement");
        }

        return $stmt;
    }
}
