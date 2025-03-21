<?php

namespace AdLDAP;

use AdLDAP\Interfaces\ConnectionInterface;

abstract class ConnectionAbstract implements ConnectionInterface
{
    /**
     * Define the different types of account in AD
     */
    public const LDAP_NORMAL_ACCOUNT = 805306368;
    public const LDAP_SECURITY_GLOBAL_GROUP = 268435456;
    public const LDAP_DISTRIBUTION_GROUP = 268435457;
    public const LDAP_FOLDER = 'OU';
    public const LDAP_CONTAINER = 'CN';

    protected AdLDAPOptions|AdLDAPSettingsInterface $options;

    /**
     * Connection constructor
     */
    public function __construct(AdLDAPOptions|AdLDAPSettingsInterface $options = new AdLDAPOptions)
    {
        $this->setOptions($options);

    }

    /**
     * Sets the options
     */
    public function setOptions(AdLDAPSettingsInterface $options): ConnectionInterface
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get the current base DN
     *
     * @return string
     */
    public function getBaseDn(): string {
        return $this->options->base_dn;
    }

    /**
     * Get the account suffix
     *
     * @return string
     */
    public function getAccountSuffix(): string
    {
        return $this->options->account_suffix;
    }
}