<?php

namespace SpiffyUser\Entity;

interface UserInterface
{
    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password);

    /**
     * @return string
     */
    public function getPassword();
}
