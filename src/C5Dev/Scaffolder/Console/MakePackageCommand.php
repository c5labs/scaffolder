<?php

/*
 * This file is part of Scaffolder.
 *
 * (c) Oliver Green <oliver@c5dev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace C5Dev\Scaffolder\Console;

use C5Dev\Scaffolder\Commands\CreatePackageCommand;
use Symfony\Component\Console\Helper\QuestionHelper as Helper;
use Symfony\Component\Console\Input\InputInterface as In;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface as Out;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class MakePackageCommand extends AbstractConsoleCommand
{
    /**
     * The name of the object that we are scaffolding.
     *
     * @var string
     */
    protected $object_name = 'Package';

    /**
     * The name of the command to dispatch to create the object.
     * 
     * @var string
     */
    protected $command_name = CreatePackageCommand::class;

    /**
     * Configure the command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
        ->setName('make:package')
        ->setDescription('Generates boilerplate package code.')
        ->setHelp('This command allows you to create packages.')
        ->addDefaultArguments()
        ->addOption('uses-composer', null, InputOption::VALUE_OPTIONAL, 'Package user composer', false)
        ->addOption('uses-providers', null, InputOption::VALUE_OPTIONAL, 'Package user composer', false);
    }

    /**
     * Custom questions for Package creation.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @param  QuestionHelper  $helper
     * @param  array           $vars
     * @return array
     */
    protected function askCustomQuestions(In $input, Out $output, Helper $helper, array $vars)
    {
        /*
         * Package Service Providers?
         */
        if (! $vars['options']['uses_service_providers'] = $input->getOption('uses-providers')) {
            $question = new ConfirmationQuestion(
                'Will this package expose any services via service providers to the core? [Y/N]:',
                false
            );
            $vars['options']['uses_service_providers'] = $helper->ask($input, $output, $question);
        }

        /*
         * Composer compatibility?
         */
        if (! $vars['options']['uses_composer'] = $input->getOption('uses-composer')) {
            $question = new ConfirmationQuestion(
                'Will you use composer to manage this packages dependencies? [Y/N]:',
                false
            );
            $vars['options']['uses_composer'] = $helper->ask($input, $output, $question);
        }

        return $vars;
    }
}