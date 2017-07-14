<?php
declare(strict_types=1);

namespace LamodaB2B\Storage;

use LamodaB2B\Model\Auth;

interface AuthStorageInterface
{
    public function get(string $identity = null): Auth;
}