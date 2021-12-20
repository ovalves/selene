<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-14
 */

namespace Selene\Auth;

use Selene\Config\ConfigConstant;
use Selene\Container\ServiceContainer;
use Selene\Gateway\GatewayAbstract;

/**
 * Manages the authentication data.
 */
class AuthGateway extends GatewayAbstract
{
    /**
     * Find user by email.
     */
    public function findByEmail(string $email) : array
    {
        $config = $this->container->get(ServiceContainer::APPLICATION_CONFIG);
        $config = $config->getConfig(ConfigConstant::AUTH);
        if (empty($config[ConfigConstant::AUTH_TABLE_NAME])) {
            throw new \Exception('Failed to check users auth data');
        }

        return $this
                ->select('*')
                ->table($config[ConfigConstant::AUTH_TABLE_NAME])
                ->where(['email = ?' => $email])
                ->execute()
                ->fetchAll();
    }

    /**
     * Register user.
     */
    public function registerUser(string $fullname, string $email, string $password): bool
    {
        $config = $this->container->get(ServiceContainer::APPLICATION_CONFIG);
        $config = $config->getConfig(ConfigConstant::AUTH);

        return (bool) $this->insert([
            'fullname' => $fullname,
            'email' => $email,
            'password' => $password,
        ])
        ->table($config[ConfigConstant::AUTH_TABLE_NAME])
        ->execute();
    }
}
