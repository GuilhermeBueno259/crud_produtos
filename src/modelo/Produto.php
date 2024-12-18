<?php
class Produto {
    /**
     * @var int
     */
    private $prod_codigo;

    /**
     * @var varchar(60)
     */
    private $prod_nome;

    /**
     * @var int
     */
    private $prod_quantidade;

    /**
     * @var decimal(4, 2)
     */
    private $prod_preco;

    /**
     * @var text
     */
    private $prod_descricao;

    public function __construct() {
    }

    public function __set($campo, $valor) {
        $this->$campo = $valor;
    }

    public function __get($campo) {
        return $this->$campo;
    }
}
