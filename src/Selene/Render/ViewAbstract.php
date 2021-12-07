<?php
/**
 * @copyright   2017 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2017-11-11
 */

namespace Selene\Render;

use Psr\Container\ContainerInterface;
use Selene\Render\Compiler\PluginCompiler;
use Selene\Render\Compiler\TemplateCompiler;
use Selene\Config\ConfigConstant;
use Selene\Container\ServiceContainer;

/**
 * Renderiza as views da aplicação
 * Faz o parser das tags especiais do template.
 */
abstract class ViewAbstract
{
    /**
     * Guarda o diretório das views.
     */
    public const APP_VIEW_DIRECTORY = 'Views/';

    /**
     * Guarda o diretório das views.
     */
    public const CACHE_VIEW_DIRECTORY = 'Views/Cache/';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Guarda o root path da aplicação.
     *
     * @var string
     */
    protected $rootPath;

    /**
     * Guarda o arquivo a ser parseado.
     *
     * @var string
     */
    protected $file;

    /**
     * Guarda o conteúdo do template a ser parseado.
     *
     * @var resource
     */
    protected $content;

    /**
     * Guarda o objeto compilador de plugin da template engine.
     *
     * @var PluginCompiler
     */
    protected $compiler;

    /**
     * Guarda o objeto compilador da template engine.
     *
     * @var TemplateCompiler
     */
    protected $template;

    /**
     * Guarda as variáveis que foram criadas no template.
     *
     * @var array
     */
    protected $assigned = [];

    /**
     * Constructor.
     */
    final public function __construct(ContainerInterface $container, PluginCompiler $compiler, TemplateCompiler $template)
    {
        $this->container = $container;
        $this->compiler = $compiler;
        $this->template = $template;
    }

    /**
     * Cria e seta um valor para uma variavel.
     *
     * @param mixed $variable
     * @param mixed $value
     */
    final public function assign($variable, $value): void
    {
        $this->assigned[$variable] = $value;
    }

    /**
     * Seta o path root da aplicação.
     */
    final public function setRootPath(string $rootPath): void
    {
        $this->rootPath = $rootPath;
    }

    /**
     * Retorna o path raiz das views.
     */
    final public function getTemplatePath(): string
    {
        if (empty($this->file)) {
            throw new ViewException('Erro ao carregar os dados da view');
        }

        return $this->rootPath . DIRECTORY_SEPARATOR . self::APP_VIEW_DIRECTORY . $this->file;
    }

    /**
     * Processa principal para criação de um template.
     *
     * @return void
     */
    final protected function make(string $file)
    {
        $this->file = $file;
        $this->content = $this->load();

        $this->makeCacheDir();
        $cachedFile = $this->getCachedFiles();

        if (empty($cachedFile)) {
            $this->parserTemplateEngine();
            $cachedFile = $this->saveToCache();
        }

        $this->requireFile($cachedFile);
    }

    /**
     * Lê o conteúdo do template a ser parseado.
     */
    final protected function load(): string
    {
        if (!file_exists($this->getTemplatePath())) {
            throw new ViewException('Erro ao carregar os dados da view');
        }

        return file_get_contents($this->getTemplatePath());
    }

    /**
     * Faz o parser do template.
     */
    final protected function parserTemplateEngine(): void
    {
        if (empty($this->content)) {
            throw new ViewException('Erro ao carregar os dados da view');
        }

        $this->content = $this->template->compilerTemplate($this->compiler, $this->file, $this->content, $this->assigned);
    }

    /**
     * Cria e seta um valor para uma variavel na renderização do template.
     */
    final protected function assignTemplateVars(array $vars): void
    {
        if (!empty($vars)) {
            foreach ($vars as $key => $value) {
                $this->assign($key, $value);
            }
        }
    }

    final protected function makeCacheDir(): void
    {
        if (!file_exists(self::CACHE_VIEW_DIRECTORY)) {
            mkdir(self::CACHE_VIEW_DIRECTORY, 0744);
        }
    }

    final protected function getCachedFiles(): ?string
    {
        $enabled = $this->container
            ->get(ServiceContainer::APPLICATION_CONFIG)
            ->getConfig(ConfigConstant::ENABLE_CACHE_VIEWS);

        if (false === $enabled) {
            return false;
        }

        $cachedFile = self::CACHE_VIEW_DIRECTORY . md5($this->file) . '.php';

        if (!file_exists($cachedFile)) {
            return false;
        }

        if (filemtime($cachedFile) < filemtime($this->getTemplatePath())) {
            return false;
        }

        return $cachedFile;
    }

    final protected function saveToCache(): string
    {
        $cachedFile = self::CACHE_VIEW_DIRECTORY . md5($this->file) . '.php';
        file_put_contents($cachedFile, $this->content);

        return $cachedFile;
    }

    final protected function requireFile(string $cachedFile): void
    {
        extract($this->assigned, EXTR_SKIP);
        require $cachedFile;
    }
}
