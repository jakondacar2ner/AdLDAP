<?php

namespace AdLDAP;

abstract class AdLDAPSettingsAbstract implements AdLDAPSettingsInterface
{
    /**
     * @inheritDoc
     */
    public function __get(string $name) {
        $getter = 'get_'.$name;

        if(method_exists($this, $getter)) {
            return $this->$getter();
        }

        throw new \InvalidArgumentException('Invalid property: '.$name);
    }

    /**
     * @inheritDoc
     */
    public function __set(string $name, $value): void {
        $setter = 'set_'.$name;

        if(method_exists($this, $setter)) {
            $this->$setter($value);
            return;
        }

        throw new \InvalidArgumentException('Invalid property: '.$name);
    }
}