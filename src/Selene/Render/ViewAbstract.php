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
        $this->parserTemplateEngine();
        echo $this->compile();
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

        $this->content = $this->template->compilerTemplate($this->compiler, $this->content, $this->assigned);
    }

    /**
     * Executa a compilação do template.
     */
    final protected function compile(): string
    {
        foreach ($this->assigned as $var => $val) {
            ${$var} = $val;
        }

        ob_start();
        eval(' ?>' . $this->content . '<?php ');

        return ob_get_clean();
    }
}
