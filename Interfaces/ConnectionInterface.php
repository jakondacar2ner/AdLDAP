<?php

declare(strict_types=1);

namespace AdLDAP\Interfaces;

use AdLDAP\AdLDAPSettingsInterface;

interface ConnectionInterface
{

    /**
     * The SSL LDAP protocol string.
     *
     * @var string
     */
    public const PROTOCOL_SSL = 'ldaps://';

    /**
     * The non-SSL LDAP protocol string.
     *
     * @var string
     */
    public const PROTOCOL = 'ldap://';

    /**
     * The LDAP SSL Port number.
     *
     * @var string
     */
    public const PORT_SSL = 636;

    /**
     * The non SSL LDAP port number.
     *
     * @var string
     */
    public const PORT = 389;

    /**
     * Sets the options
     */
    public function setOptions(AdLDAPSettingsInterface $options):ConnectionInterface;

    /**
     * Binds the connection against a user's DN and password.
     */
    public function bind(?string $dn = null, #[\SensitiveParameter] ?string $pw = null): void;

    /**
     * Get the bind status
     *
     * @return bool
     */
    public function isBound(): bool;
}