<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-14
 */

namespace Selene\Auth;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Selene\Config\ConfigConstant;
use Selene\Container\ServiceContainer;
use Selene\Session\Session;
use Selene\Session\SessionConstant;

/**
 * Trata as solicitaçoes de autenticacao do framework.
 */
class Auth
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Guarda o objeto de sessão.
     *
     * @var Session
     */
    protected $session;

    /**
     * Guarda os dados do user.
     *
     * @var mixed
     */
    protected $user;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(ContainerInterface $container, Session $session)
    {
        $this->container = $container;
        $this->session = $session;
        $this->applicationConfig = $this->container->get(ServiceContainer::APPLICATION_CONFIG);

        $this->container->setPrefix(AuthConstant::AUTH_TABLE)->set(
            AuthGateway::class
        );
    }

    /**
     * Seta os dados da requisição de autenticacao.
     */
    public function setRequest(ServerRequestInterface $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Verifica se o user está autenticado.
     */
    public function isAuthenticated(): bool
    {
        if (!$this->session->hasSession()) {
            return false;
        }

        if ($this->session->shouldRegenerateSessionId()) {
            $this->session->regenerateSessionId();

            $config = $this->applicationConfig->getConfig(ConfigConstant::SESSION);
            $this->session->setValue(
                [
                    SessionConstant::UPDATED_AT => strtotime('now'),
                    SessionConstant::CREATED_AT => strtotime('now'),
                    SessionConstant::EXPIRATION_TIME => strtotime("+ {$config[ConfigConstant::SESSION_EXPIRATION_TIME]} seconds"),
                    SessionConstant::REFRESH_TIME => strtotime("+ {$config[ConfigConstant::SESSION_REFRESH_TIME]} seconds"),
                ]
            );
        }

        return true;
    }

    /**
     * Autentica user.
     */
    public function authenticate(string $email, string $password): bool
    {
        $storedPassword = $this->generateFakePassword($password);
        $this->user = $this->findByEmail($email);

        if ($this->user) {
            $storedPassword = $this->user[0]['password'];
        }

        return (bool) ($this->verifyPassword($password, $storedPassword)) && (null !== $this->user);
    }

    /**
     * Registra o usuário.
     */
    public function registerUser(string $fullname, string $email, string $password): bool
    {
        $storeInDatabase = \sodium_crypto_pwhash_str(
            $password,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
        );

        $authGateway = $this->container->get(AuthConstant::AUTH_TABLE);

        return (bool) $authGateway->registerUser($fullname, $email, $storeInDatabase);
    }

    /**
     * Retorna o user autenticado.
     */
    public function getUser(): mixed
    {
        if ($this->session->hasSession()) {
            return $this->user;
        }
    }

    /**
     * Desloga o user autenticado.
     */
    public function logout(): bool
    {
        if ($this->session->hasSession()) {
            $this->session->freeSession();

            return true;
        }

        return false;
    }

    /**
     * Retorna a página de login.
     */
    public function redirectToLoginPage(): string
    {
        $config = $this->applicationConfig->getConfig(ConfigConstant::AUTH);

        return $config[ConfigConstant::AUTH_LOGIN_URL];
    }

    /**
     * Find user by email.
     */
    protected function findByEmail(string $email): array
    {
        $authGateway = $this->container->get(AuthConstant::AUTH_TABLE);

        return $authGateway->findByEmail($email);
    }

    /**
     * Generate fake password.
     */
    protected function generateFakePassword($password): string
    {
        $genericSecretKey = random_bytes(32);

        return sodium_crypto_generichash($password, $genericSecretKey);
    }

    /**
     * Verifica o password.
     */
    protected function verifyPassword(string $password, string $storedPassword): bool
    {
        if (!\sodium_crypto_pwhash_str_verify($storedPassword, $password)) {
            return false;
        }

        $config = $this->applicationConfig->getConfig(ConfigConstant::SESSION);
        $this->session->setValue(
            [
                SessionConstant::USER_ID => $this->user[0]['user_id'],
                SessionConstant::USER_DATA => $this->user[0],
                SessionConstant::UPDATED_AT => strtotime('now'),
                SessionConstant::CREATED_AT => strtotime('now'),
                SessionConstant::EXPIRATION_TIME => strtotime("+ {$config[ConfigConstant::SESSION_EXPIRATION_TIME]} seconds"),
                SessionConstant::REFRESH_TIME => strtotime("+ {$config[ConfigConstant::SESSION_REFRESH_TIME]} seconds"),
            ]
        );

        return true;
    }
}
