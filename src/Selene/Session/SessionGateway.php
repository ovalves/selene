<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Selene\Session;

use Selene\Gateway\GatewayAbstract;
use Selene\Config\ConfigConstant;
use Selene\Session\SessionException;
use Selene\Container\ServiceContainer;

/**
 * Manages the session data
 */
class SessionGateway extends GatewayAbstract
{
    /**
     * Get session by id
     */
    public function getSessionById(string $sessionId)
    {
        $config = $this->container->get(ServiceContainer::APPLICATION_CONFIG);
        $config = $config->getConfig(ConfigConstant::SESSION);

        if (empty($config[ConfigConstant::SESSION_TABLE_NAME])) {
            throw new SessionException("Failed to check user session data");
        }

        return $this
                ->select('*')
                ->table($config[ConfigConstant::SESSION_TABLE_NAME])
                ->where(['session_id = ?' => $sessionId])
                ->execute()
                ->fetchAll();
    }
}
