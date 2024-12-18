<?php
namespace App\Controller;

use App\Dao\UsuarioDao;

class ControllerUsuario {
    /**
     * @var UsuarioDao
     */
    private UsuarioDao $usuario_dao;

    public function __construct(&$usuario_dao) {
        $this->usuario_dao = $usuario_dao;
    }

    public function entrar() {
        $resultado_pesquisa = $this->usuario_dao->pesquisar();

        if (count($resultado_pesquisa) == 1) {
            http_response_code(200);
            die(json_encode(array('mensagem' => 'Logar usuário')));
        } else {
            http_response_code(400);
            die(json_encode(array('mensagem' => 'Dados inválidos')));
        }
    }

    public function cadastrar() {
        $resultado_operacao = $this->usuario_dao->gravar();

        echo '<pre>';
        var_dump($resultado_operacao);
        echo '</pre>';
    }
}
