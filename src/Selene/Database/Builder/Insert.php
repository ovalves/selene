<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-24
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
final class Insert extends GrammarAbstract
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

        $stringSql = \str_replace(
            [
                '__TABLENAME__',
                '__FIELDS__',
                '__VALUES__',
            ],
            [
                $this->table,
                \implode(',', $this->fields),
                \implode(',', $this->bindParam),
            ],
            $this->grammar[DatabaseConstant::INSERT]
        );

        if (empty($stringSql)) {
            throw new GrammarException('Erro ao parser os dados da query');
        }

        $stmt = $this->transaction->open()->prepare($stringSql);
        if (!$stmt->execute(\array_combine($this->bindParam, $this->values))) {
            throw new GrammarException('Erro ao executar o statement');
        }

        return $stmt;
    }
}
