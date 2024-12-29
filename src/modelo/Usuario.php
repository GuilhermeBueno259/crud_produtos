<?php

namespace App\modelo;

class Usuario {

    /**
     * @var int
     */
    private $usua_codigo;

    /**
     * @var varchar(20)
     */
    private $usua_nome;

    /**
     * @var varchar(20)
     */
    private $usua_login;

    /**
     * @var varchar(20)
     */
    private $usua_senha;

    /**
     * @var varchar(200)
     */
    private $usua_token;

    /**
     * @var datetime
     */
    private $usua_expiracao_token;

    public function __construct() {
    }

    public function __set($campo, $valor) {
        $this->$campo = $valor;
    }

    public function __get($campo) {
        return $this->$campo;
    }

    public function retornarValores() {
        return array_filter(get_object_vars($this), function ($valor) {
            return isset($valor);
        });
    }
}
