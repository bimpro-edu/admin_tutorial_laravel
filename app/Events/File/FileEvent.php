<?php

namespace ThaoHR\Events\File;

use ThaoHR\File;

abstract class FileEvent
{
    /**
     * @var File
     */
    protected $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }
}