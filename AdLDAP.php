<?php

declare(strict_types=1);

namespace AdLDAP;

use AdLDAP\Interfaces\ConnectionInterface;
use LDAP\Connection as LDAPConnection;
use RuntimeException;

class AdLDAP extends ConnectionAbstract
{
    protected ?LDAPConnection $connection = null;
    protected bool $bound = false;

    /**
     * Default Destructor
     *
     * @return void
     */
    function __destruct()
    {
        $this->close();
    }

    /**
     * Connects to the Domain Controller
     *
     * @return void
     */
    public function connect(): void
    {
        // Пока не понятно надо ли эту хуйню проверять или нет, пока оставлю
        if ($this->connection) {
            return;
        }

        $dc = $this->findAvailableServer()
            ?? throw new RuntimeException('No available domain controllers');

        $protocol = $this->options->use_ssl ? self::PROTOCOL_SSL : self::PROTOCOL;
        $port = $this->options->use_ssl ? ConnectionInterface::PORT_SSL : $this->options->port;

        $this->connection = ldap_connect("$protocol$dc", $port);
        if (!$this->connection) {
            throw new RuntimeException("AdLDAP to $dc failed: " . $this->getLastError());
        }

        ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->connection, LDAP_OPT_REFERRALS, 0);

        if ($this->options->use_tls) {
            ldap_set_option(null, LDAP_OPT_X_TLS_REQUIRE_CERT, LDAP_OPT_X_TLS_NEVER);
            ldap_start_tls($this->connection);
        }
    }

    /**
     *
     *
     * @param string|null $dn
     * @param string|null $pw
     * @return void
     */
    public function bind(?string $dn = null, #[\SensitiveParameter] ?string $pw = null): void
    {
        if (!$this->connection) {
            $this->connect();
        }

        if (false === @ldap_bind($this->connection, $this->options->bind_dn, $this->options->bind_pw)) {
            ldap_get_option($this->connection, LDAP_OPT_DIAGNOSTIC_MESSAGE, $diagnostic);
            throw new RuntimeException("Bind failed: " . $this->getLastError() . ". Diagnostic "
                . $diagnostic);
        }
        
        $this->bound = true;
    }

    /**
     * Get the bind status
     *
     * @return bool
     */
    public function isBound(): bool {
        return $this->bound;
    }

    /**
     * Closes the LDAP connection
     *
     * @return void
     */
    public function close(): void
    {
        if ($this->connection) {
            @ldap_close($this->connection);
        }
    }

    /**
     * Get the active LDAP AdLDAP
     *
     * @return LDAPConnection|null
     */
    public function getConnection(): ?LDAPConnection
    {
        return $this->connection;
    }

    private function disconnect(): void
    {
        if ($this->connection) {
            ldap_unbind($this->connection);
        }

        $this->connection = null;
        $this->bound = false;
    }
    
    /**
     * Get the RootDSE properties from a domain controller
     *
     * @param array $attributes The attributes you wish to query e.g. defaultnamingcontext
     * @return array
     */
    public function getRootDSE(array $attributes = ["*", "+"]): array
    {
        if (!$this->bound) {
            throw new RuntimeException('LDAP bind not established');
        }

        $sr = ldap_read($this->connection, '', 'objectClass=*', $attributes);
        if (!$sr) {
            throw new RuntimeException('RootDSE read failed: ' . $this->getLastError());
        }

        $entries = ldap_get_entries($this->connection, $sr);
        return $entries ?: [];
    }


    /**
     * Find the Base DN of your domain controller
     *
     * @return string
     */
    public function findBaseDN(): string
    {
        $namingContext = $this->getRootDSE(['defaultnamingcontext']);
        return $namingContext[0]['defaultnamingcontext'][0] ?? '';
    }

    /**
     * Set the account suffix
     *
     * @return void
     * @throws RuntimeException
     */
    protected function setAccountSuffix(): void
    {
        if (!$this->options->base_dn) {
            $this->options->base_dn = $this->findBaseDN();
        }

        $parts = explode(',', $this->options->base_dn);
        $domainParts = [];

        foreach ($parts as $part) {
            if (stripos($part, 'DC=') === 0) {
                $domainParts[] = strtolower(str_replace('DC=', '', $part));
            }
        }

        if (empty($domainParts)) {
            throw new RuntimeException('Could not determine account suffix from Base DN');
        }

        $this->options->account_suffix = '@' . implode('.', $domainParts);
    }

    /**
     * Select available domain controller from your domain controller array
     *
     * @return string|null
     */
    public function findAvailableServer(): ?string
    {
        foreach ($this->options->domain_controllers as $controller) {
            $port = $this->options->port;
            $fp = @fsockopen($controller, $port, $errno, $errstr, 5);

            if ($fp !== false) {
                fclose($fp);
                return $controller;
            }

            error_log("Host $controller is unreachable: $errstr ($errno)");
        }

        return null;
    }

    /**
     * Get last error from Active Directory
     *
     * This function gets the last message from Active Directory
     * This may indeed be a 'Success' message but if you get an unknown error
     * it might be worth calling this function to see what errors were raised
     *
     * @return string
     */
    public function getLastError(): string
    {
        if ($this->connection instanceof LDAPConnection) {
            return ldap_error($this->connection);
        }

        return 'LDAP bind not established';
    }
}
