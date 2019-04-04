<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-02-09
 */

namespace  Vindite\Database;

/**
 * Define as constantes usadas no router
 */
class DatabaseConstant
{
    /**
     * Define o nome da base de dados
     */
    const DATABASE_NAME = 'database_connection';

    /**
     * Define o prefixo do container de connection
     */
    const CONNECTION = 'connection';

    /**
     * Define o prefixo do container de logger
     */
    const LOGGER = 'logger';

    /**
     * Define o prefixo do container de transaction
     */
    const TRANSACTION = 'transaction';

    /**
     * Define o tipo de expressão a ser avaliada
     */
    const INSERT = 'insert';

    /**
     * Define o tipo de expressão a ser avaliada
     */
    const SELECT = 'select';

    /**
     * Define o tipo de expressão a ser avaliada
     */
    const UPDATE = 'update';

    /**
     * Define o tipo de expressão a ser avaliada
     */
    const DELETE = 'delete';
}
