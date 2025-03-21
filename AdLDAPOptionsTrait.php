<?php

declare(strict_types=1);

namespace AdLDAP;

use AdLDAP\Interfaces\ConnectionInterface;

/**
 * @property array    $domain_controllers
 * @property int      $port
 * @property string   $base_dn
 * @property string   $bind_dn
 * @property string   $bind_pw
 * @property string   $admin_username
 * @property string   $admin_password
 * @property string   $account_suffix
 * @property bool     $use_ssl
 * @property bool     $use_tls
 * @property bool     $use_sso
 * @property bool     $recursive_groups
 */
trait AdLDAPOptionsTrait
{

    /**
     * Массив контроллеров домена
     * Не может быть пустым
     *
     * @var array
     */
    protected array $domain_controllers = [];

    /**
     * Порт для подключения к контроллеру домена
     *
     * @var int
     */
    protected int $port = ConnectionInterface::PORT;

    /**
     * BaseDN
     *
     * @var string
     */
    protected ?string $base_dn = null;

    /**
     * Аккаунт для просмотра Ldap каталога, в случае если запрещено анонимное подключение
     *
     * @var string
     * @var string
     */
    protected ?string $bind_dn = null;
    protected ?string $bind_pw = null;

    /**
     * Опционально. Административная учетная запись
     *
     * @var string
     * @var string
     */
    protected ?string $admin_username = null;
    protected ?string $admin_password = null;

    /**
     * Суффикс домена
     *
     * @var string
     */
    protected ?string $account_suffix = null;

    /**
     * Use SSL (LDAPS)
     *
     * @var bool
     */
    protected bool $use_ssl = false;

    /**
     * Use TLS
     *
     * @var bool
     */
    protected bool $use_tls = false;

    /**
     * Use SSO
     *
     * @var bool
     */
    protected bool $use_sso = false;

    /**
     * Рекурсивный просмотр членства в группах
     *
     * @var bool
     */
    protected bool $recursive_groups = true;

    protected function get_domain_controllers(): array {
        return $this->domain_controllers;
    }

    protected function get_port(): int {
        return $this->port;
    }

    protected function get_base_dn(): ?string {
        return $this->base_dn;
    }

    protected function get_bind_dn(): ?string {
        return $this->bind_dn;
    }

    protected function get_bind_pw(): ?string {
        return $this->bind_pw;
    }

    protected function get_admin_username(): ?string {
        return $this->admin_username;
    }

    protected function get_admin_password(): ?string {
        return $this->admin_password;
    }

    protected function get_account_suffix(): ?string {
        return $this->account_suffix;
    }

    protected function get_use_ssl(): bool {
        return $this->use_ssl;
    }

    protected function get_use_tls(): bool {
        return $this->use_tls;
    }

    protected function get_use_sso(): bool {
        return $this->use_sso;
    }

    protected function get_recursive_groups(): bool {
        return $this->recursive_groups;
    }

    /**
     * Set domain controllers
     */
    protected function set_domain_controllers(array $domain_controllers): void
    {
        $this->domain_controllers = $domain_controllers;
    }

    /**
     * Set AD port
     */
    protected function set_port(int $value): void
    {
        $this->port = $value;
    }

    /**
     * Set base DN
     */
    protected function set_base_dn(?string $value): void
    {
        $this->base_dn = $value;
    }

    /**
     * Set bind DN
     */
    protected function set_bind_dn(?string $value): void
    {
        $this->bind_dn = $value;
    }

    /**
     * Set bind password
     */
    protected function set_bind_pw(?string $value): void
    {
        $this->bind_pw = $value;
    }

    /**
     * Set admin username
     */
    protected function set_admin_username(?string $value): void
    {
        $this->admin_username = $value;
    }

    /**
     * Set admin password
     */
    protected function set_admin_password(?string $value): void
    {
        $this->admin_password = $value;
    }

    /**
     * Set account suffix
     */
    protected function set_account_suffix(?string $value): void
    {
        $this->account_suffix = $value;
    }

    /**
     * Set SSL usage
     */
    protected function set_use_ssl(bool $value): void
    {
        $this->use_ssl = $value;
    }

    /**
     * Set TLS usage
     */
    protected function set_use_tls(bool $value): void
    {
        $this->use_tls = $value;
    }

    /**
     * Set SSO usage
     */
    protected function set_use_sso(bool $value): void
    {
        $this->use_sso = $value;
    }

    /**
     * Set recursive groups
     */
    protected function set_recursive_groups(bool $value): void
    {
        $this->recursive_groups = $value;
    }
}
