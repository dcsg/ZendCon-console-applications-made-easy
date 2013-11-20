<?php
/**
 * This file is part of the examples package.
 *
 * (c) Daniel Gomes <me@danielcsgomes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DCSG;

use DCSG\Command\AskAndValidateCommand;
use DCSG\Command\CallingCommandInsideCommand;
use DCSG\Command\CatchSignalsCommand;
use DCSG\Command\DumpDatabaseDICommand;
use DCSG\Command\FormatterHelperCommand;
use DCSG\Command\HelloWorldCommand;
use DCSG\Command\ProgressHelperCommand;
use DCSG\Command\TableHelperCommand;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Application extends BaseApplication implements ContainerAwareInterface
{
    const VERSION = '0.0.x';
    /**
     * @var string
     */
    protected $baseDir;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct()
    {
        $this->baseDir = __DIR__ . '/../..';

        parent::__construct('My Console App', self::VERSION);

        $this->loadContainerDefinitions();

        $this->add(new DumpDatabaseDICommand());
        $this->add(new HelloWorldCommand());
        $this->add(new TableHelperCommand());
        $this->add(new ProgressHelperCommand());
        $this->add(new AskAndValidateCommand());
        $this->add(new CallingCommandInsideCommand());
        $this->add(new FormatterHelperCommand());
        $this->add(new CatchSignalsCommand());
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Loads and configures the DI Container definitions
     */
    private function loadContainerDefinitions()
    {
        $configDirectories = array(
            __DIR__ . '/../../config'
        );

        $container = new ContainerBuilder();

        $loader = new YamlFileLoader($container, new FileLocator($configDirectories));
        $loader->load('parameters.yaml');

        $this->setContainer($container);
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }
} 