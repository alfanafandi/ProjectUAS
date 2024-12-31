<?php
class User
{
    public $user_id;
    public $user_username;
    public $user_password;
    public $saldo;

    function __construct($user_id, $user_username, $user_password, $saldo)
    {
        $this->user_id = $user_id;
        $this->user_username = $user_username;
        $this->user_password = $user_password;
        $this->saldo = $saldo;
    }
}
