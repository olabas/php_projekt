<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Model;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

final class User implements AdvancedUserInterface
{
    private $username;
    private $password;
    private $name;
    private $surname;
    private $address;
    private $email;
    private $phone_number;
    private $enabled;
    private $accountNonExpired;
    private $credentialsNonExpired;
    private $accountNonLocked;
    private $roles;


    public function __construct(
        $username,
        $password,
        $name,
        $surname,
        $address,
        $email,
        $phone_number,
        array $roles = array(),
        $enabled = true,
        $userNonExpired = true,
        $credentialsNonExpired = true,
        $userNonLocked = true
    ) {
        if (empty($username)) {
            throw new \InvalidArgumentException('The username cannot be empty.');
        }

        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
        $this->surname = $surname;
        $this->address = $address;
        $this->email = $email;
        $this->phone_number = $phone_number;
        $this->enabled = $enabled;
        $this->accountNonExpired = $userNonExpired;
        $this->credentialsNonExpired = $credentialsNonExpired;
        $this->accountNonLocked = $userNonLocked;
        $this->roles = $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string User's name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string User's surname
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @return string User's address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string User's email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string User's phone number
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * @return string User's Salt
     */
    public function getSalt()
    {
    }

    /**
     * @return string User's name
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string Is account non expired
     */
    public function isAccountNonExpired()
    {
        return $this->accountNonExpired;
    }

    /**
     * @return string Is account non locked
     */
    public function isAccountNonLocked()
    {
        return $this->accountNonLocked;
    }

    /**
     * {@return string is Credentials non expired
     */
    public function isCredentialsNonExpired()
    {
        return $this->credentialsNonExpired;
    }

    /**
     * @return string is enabled
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}
