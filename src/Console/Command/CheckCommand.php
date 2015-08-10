<?php

/**
 * This file is part of the php7-checker project.
 *
 * (c) Loïck Piera <pyrech@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Joli\Php7Checker\Console\Command;

use Joli\Php7Checker\Error\Error;
use Joli\Php7Checker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * This command use the parser to check if the targeted code can run on PHP 7.
 */
class CheckCommand extends Command
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('check')
            ->setDefinition(
                array(
                    new InputArgument('path', InputArgument::OPTIONAL, 'The path', null),
                )
            )
            ->setDescription('Check a directory or a file')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command parse php files to detect some errors that will prevent your code to run on PHP 7:

    <info>php %command.full_name% /path/to/dir</info>
    <info>php %command.full_name% /path/to/file</info>

EOF
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var FormatterHelper $formatter */
        $formatter = $this->getHelperSet()->get('formatter');

        $path = $input->getArgument('path') ?: '';

        $filesystem = new Filesystem();
        if (!$filesystem->isAbsolutePath($path)) {
            $path = getcwd().DIRECTORY_SEPARATOR.$path;
        }

        $path = rtrim(realpath($path), DIRECTORY_SEPARATOR);

        if (is_file($path)) {
            $files = new \ArrayIterator(array(new \SplFileInfo($path)));
        } else {
            $path .= DIRECTORY_SEPARATOR;
            $files = new Finder();

            $files
                ->files()
                ->name('*.php')
                ->ignoreDotFiles(true)
                ->ignoreVCS(true)
                ->exclude('vendor')
                ->in($path)
            ;
        }

        $output->writeln(sprintf("\rChecking %s", $path));
        $total = $files->count();
        $parser = Factory::createParser();

        $index = 1;
        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            $output->write(sprintf("\rParsing file %s/%s", $index, $total));
            $parser->parse($file);
            ++$index;
        }

        $output->writeln(PHP_EOL);

        $errorCollection = $parser->getErrorCollection();
        $errorCount = count($errorCollection);

        if ($errorCount > 0) {
            $message = $formatter->formatBlock(
                sprintf(
                    '%s %s found on your code.',
                    $errorCount,
                    $errorCount > 1 ? 'errors were' : 'error was'
                ),
                'error',
                true
            );
            $output->writeln($message);
            $output->writeln('');

            /** @var Error $error */
            foreach ($errorCollection as $error) {
                $output->writeln(sprintf('    %s', $error->getMessage()));
                if ($error->getHelp()) {
                    $output->writeln(sprintf('    > %s', $error->getHelp()));
                }
                $output->writeln(sprintf(
                    '        <fg=cyan>%s</fg=cyan> on line <fg=cyan>%s</fg=cyan>',
                    str_replace($path, '', $error->getFilename()),
                    $error->getLine()
                ));
                $output->writeln('');
            }

            return 1;
        }

        $message = $formatter->formatBlock('No error was found when checking your code.', 'info', false);
        $output->writeln($message);
        $output->writeln('');

        $message = $formatter->formatBlock(array(
            'Note:',
            '> As this tool only do a static analyze of your code, you cannot blindly rely on it.',
            '> The best way to ensure that your code can run on PHP 7 is still to run a complete test suite on the targeted version of PHP.',
        ), 'comment', false);
        $output->writeln($message);
        $output->writeln(PHP_EOL);

        return 0;
    }
}
