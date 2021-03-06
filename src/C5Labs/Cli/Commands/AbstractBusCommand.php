<?php

/*
 * This file is part of Cli.
 *
 * (c) Oliver Green <oliver@c5labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace C5Labs\Cli\Commands;

use Illuminate\Contracts\Console\Application;
use C5Labs\Cli\FileExporter\FileExporter;
use Illuminate\Contracts\Bus\SelfHandling;

abstract class AbstractBusCommand implements SelfHandling
{
    /**
     * Handle.
     *
     * @var string
     */
    protected $handle;

    /**
     * Name.
     *
     * @var string
     */
    protected $name;

    /**
     * Description.
     *
     * @var string
     */
    protected $description;

    /**
     * Author.
     *
     * @var string
     */
    protected $author;

    /**
     * Options.
     *
     * @var array
     */
    protected $options;

    /**
     * Path.
     *
     * @var string
     */
    protected $path;

    /**
     * Constructor.
     *
     * @param string $path
     * @param string $handle
     * @param string $name
     * @param string $description
     * @param string $author
     * @param array $options
     */
    public function __construct($path, $handle, $name, $description, array $author, $options = null)
    {
        $this->handle = $handle;

        $this->name = $name;

        $this->description = $description;

        $this->author = $author;

        $this->options = $options ?: [];

        $this->path = $path;
    }

    /**
     * Forms a path from an array of parts.
     * 
     * @param  array  $parts
     * @return string
     */
    protected function makePath(array $parts)
    {
        return implode(DIRECTORY_SEPARATOR, $parts);
    }

    /**
     * Creates a package for our object and returns the handle.
     *
     * @param  Application $app
     * @param  array $options
     * @return string
     */
    protected function createPackage(Application $app, $handle, array $options)
    {
        $package_name = $this->name.' Package';

        $app->make(\Illuminate\Contracts\Bus\Dispatcher::class)->dispatch(
            new CreatePackageCommand(
                $this->path, $handle, $package_name,
                $this->description, $this->author, $options
            )
        );

        return $handle;
    }

    /**
     * Handle the command.
     * 
     * @param  Application $app      
     * @param  FileExporter $exporter 
     * @return bool                                        
     */
    abstract public function handle(Application $app, FileExporter $exporter);
}
