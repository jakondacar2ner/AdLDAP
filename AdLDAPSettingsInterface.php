<?php

declare(strict_types=1);

namespace AdLDAP;

interface AdLDAPSettingsInterface
{
    /**
     * Magic getter for protected properties
     */
    public function __get(string $name);

    /**
     * Magic setter for protected properties
     */
    public function __set(string $name, $value): void;

}