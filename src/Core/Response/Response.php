<?php /** @noinspection PhpIncludeInspection */


namespace App\Core\Response;


use App\Core\ApplicationContext;

class Response implements ResponseInterface
{
    /**
     * @var ApplicationContext
     */
    private $context;

    /**
     * @var string
     */
    private $page;

    /**
     * @var array
     */
    private $args;

    /**
     * Response constructor.
     * @param ApplicationContext $context
     * @param string $page
     * @param array $args
     */
    public function __construct(ApplicationContext $context, string $page, array $args = [])
    {
        $this->context = $context;
        $this->page = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $page)), '_');
        $this->args = $args;
    }

    /**
     * @return string
     */
    public function render(): string
    {

        if(!file_exists("{$this->context->getConfig()->getPagesDir()}/{$this->page}.php")) {
            return $this->__load("errors/404");
        }

        return $this->__load($this->page);
    }

    /**
     * @param string $page
     * @return string
     */
    private function __load(string $page): string {
        extract($this->args);
        $context = $this->context;

        ob_start();
        include("{$this->context->getConfig()->getPagesDir()}/{$this->page}.php");
        return ob_get_clean();
    }
}
