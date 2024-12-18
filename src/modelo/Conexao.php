<?php
namespace App\Modelo;

class Conexao {
    private $conexao;

    public function __construct() {
        $dsn = DRIVER . ':host=' . HOST . ';port=' . PORTA . ';dbname=' . BANCO_DE_DADOS;
        $this->conexao = new \PDO($dsn, USUARIO_BANCO, SENHA_BANCO);
    }

    public function executar($sql, $valores_busca = []) {
        $comando = $this->conexao->prepare($sql);
        $comando->execute($valores_busca);

        if (strpos($sql, 'SELECT') === 0) {
            return $comando->fetchAll(\PDO::FETCH_ASSOC);
        } else if (strpos($sql, 'INSERT') === 0) {
            return $this->conexao->lastInsertId();
        } else {
            return $comando->rowCount();
        }
    }
}