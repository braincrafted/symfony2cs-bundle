<?php
/**
 * This file is part of BraincraftedSymfony2CSBundle.
 *
 * (c) 2013 Florian Eckerstorfer <florian@eckerstorfer.co>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Braincrafted\Bundle\Symfony2CSBundle\Composer;

use Symfony\Component\ClassLoader\ClassCollectionLoader;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Composer\Script\CommandEvent;

/**
 * ScriptHandler
 *
 * @package    BraincraftedSymfony2CSBundle
 * @subpackage Composer
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 *
 * @codeCoverageIgnore
 */
class ScriptHandler
{
    /**
     * @param CommandEvent $event
     */
    public static function installSymfony2CS(CommandEvent $event)
    {
        $options = self::getOptions($event);
        $appDir = $options['symfony-app-dir'];

        if (!is_dir($appDir)) {
            printf(
                'The symfony-app-dir (%s) specified in composer.json was not found in %s, can not build bootstrap '.
                'file.%s',
                $appDir,
                getcwd(),
                PHP_EOL
            );

            return;
        }

        static::executeCommand($event, $appDir, 'braincrafted:symfony2cs:install', $options['process-timeout']);
    }

    /**
     * @param CommandEvent $event
     * @param string       $appDir
     * @param string       $cmd
     * @param integer      $timeout
     *
     * @return void
     *
     * @throws \RunTimeException when an error occurred when executing the command
     */
    protected static function executeCommand(CommandEvent $event, $appDir, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(self::getPhp());
        $console = escapeshellarg($appDir.'/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php.' '.$console.' '.$cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                sprintf('An error occurred when executing the "%s" command.', escapeshellarg($cmd))
            );
        }
    }

    /**
     * @param CommandEvent $event
     *
     * @return array
     */
    protected static function getOptions(CommandEvent $event)
    {
        $options = array_merge(array(
            'symfony-app-dir' => 'app',
            'symfony-web-dir' => 'web',
            'symfony-assets-install' => 'hard'
        ), $event->getComposer()->getPackage()->getExtra());

        $options['symfony-assets-install'] = getenv('SYMFONY_ASSETS_INSTALL') ?: $options['symfony-assets-install'];

        $options['process-timeout'] = $event->getComposer()->getConfig()->get('process-timeout');

        return $options;
    }

    /**
     * @return string Path to php executable
     */
    protected static function getPhp()
    {
        $phpFinder = new PhpExecutableFinder;
        if (!$phpPath = $phpFinder->find()) {
            throw new \RuntimeException(
                'The php executable could not be found, add it to your PATH environment variable and try again'
            );
        }

        return $phpPath;
    }
}
