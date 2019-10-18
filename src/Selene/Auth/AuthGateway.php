<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-14
 */

namespace Selene\Auth;

use Selene\Gateway\GatewayAbstract;
use Selene\Config\ConfigConstant;

/**
 * Manages the authentication data
 */
class AuthGateway extends GatewayAbstract
{
    use \Selene\Config\ConfigAwareTrait;

    /**
     * Find user by email
     *
     * @param string $email
     * @return array
     */
    public function findByEmail(string $email) : array
    {
        $config = $this->loadConfig(ConfigConstant::AUTH);

        if (empty($config[ConfigConstant::AUTH_TABLE_NAME])) {
            throw new \Exception("Failed to check user auth data");
        }

        return $this
                ->select('*')
                ->table($config[ConfigConstant::AUTH_TABLE_NAME])
                ->where(['email = ?' => $email])
                ->execute()
                ->fetchAll();
    }

    /**
     * Register user
     *
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function registerUser(string $email, string $password) : bool
    {
        return (bool) $this->insert([
            'email' => $email,
            'password' => $password,
        ])
        ->table('user')
        ->execute();
    }
}
