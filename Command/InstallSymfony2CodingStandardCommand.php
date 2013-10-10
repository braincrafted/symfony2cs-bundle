<?php
/**
 * This file is part of BcSymfony2CodingStandardBundle.
 *
 * (c) 2013 Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Bc\Bundle\Symfony2CodingStandardBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * InstallSymfony2CodingStandardCommand
 *
 * @package    BcSymfony2CodingStandardBundle
 * @subpackage Command
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 *
 * @codeCoverageIgnore
 */
class InstallSymfony2CodingStandardCommand extends Command
{
    /** @var string */
    private $rootDir;

    /**
     * Constructor.
     *
     * @param string $rootDir Root directory
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('bc:symfony2cs:install');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $vendorDir = sprintf('%s/../vendor', $this->rootDir);
        $codeSnifferDir = sprintf('%s/squizlabs/php_codesniffer', $vendorDir);

        if (false === file_exists($codeSnifferDir)) {
            $output->writeln('<error>Could not find squizlabs/php_codesniffer. Is it installed?</error>');
        }

        $command = 'git clone git://github.com/opensky/Symfony2-coding-standard.git Symfony2';
        $cwd = sprintf('%s/CodeSniffer/Standards', $codeSnifferDir);
        $update = false;

        if (true === file_exists(sprintf('%s/Symfony2', $cwd))) {
            $command = 'git pull origin master';
            $cwd = sprintf('%s/Symfony2', $cwd);
            $update = true;
        }

        $process = new Process($command, $cwd);
        $process->run(function ($type, $buffer) use ($input, $output) {
            if (true === $input->getOption('verbose')) {
                $output->writeln(sprintf('<comment>%s</comment>', $buffer));
            }
        });

        if (false === $process->isSuccessful()) {
            throw new \RuntimeException(
                sprintf('An error occurred when executing the "%s" command.', escapeshellarg($command))
            );
        }

        if (true === $update) {
            $output->writeln('<info>Updated Symfony2 coding standard for PHP_CodeSniffer.</info>');

            return;
        }

        $output->writeln('<info>Installed Symfony2 coding standard for PHP_CodeSniffer.</info>');
    }
}
