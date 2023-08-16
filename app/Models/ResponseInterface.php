<?php

namespace App\Models;

/**
 * Interface ResponseInterface
 * @package App\Helpers
 */
interface ResponseInterface
{
    public function add($sourceRes);

    public function get();
}
