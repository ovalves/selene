<?php
/**
 * @copyright   2019 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

namespace Vindite\Session;

use Vindite\Gateway\GatewayAbstract;
use Vindite\Config\ConfigConstant;
use Vindite\Session\SessionException;

/**
 * Manages the session data
 */
class SessionGateway extends GatewayAbstract
{
    use \Vindite\Config\ConfigAwareTrait;

    /**
     * Get session by id
     */
    public function getSessionById(string $sessionId)
    {
        $config = $this->loadConfig(ConfigConstant::SESSION);

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
