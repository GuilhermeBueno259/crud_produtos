<?php

namespace App\Controller;

use App\Dao\UsuarioDao;
use App\Excessoes\ExcessaoDadosInvalidos;
use Firebase\JWT\JWT;

class ControllerUsuario {
    /**
     * @var UsuarioDao
     */
    private UsuarioDao $usuario_dao;

    public function __construct(&$usuario_dao) {
        $this->usuario_dao = $usuario_dao;
    }

    public function autenticar() {
        $resultado_pesquisa = $this->usuario_dao->pesquisar();

        if (count($resultado_pesquisa) == 1) {
            $expiracao_token_em_segundos = time() + 3600;
            $data_expiracao_token = date('Y-m-d H:i:s', $expiracao_token_em_segundos);

            $token = JWT::encode(['iss' => 'localhost:8080', 'aud' => 'localhost:8080', 'iat' => time(), 'nbf' => $expiracao_token_em_segundos], 'abc', 'HS256');

            $this->usuario_dao->usuario->__set('usua_codigo', $resultado_pesquisa[0]['usua_codigo']);
            $this->usuario_dao->usuario->__set('usua_token', $token);
            $this->usuario_dao->usuario->__set('usua_expiracao_token', $data_expiracao_token);
            $this->usuario_dao->editar();

            return ['mensagem' => 'Usuário autenticado', 'token' => $token, 'expiracao' => $data_expiracao_token];
        } else {
            throw new ExcessaoDadosInvalidos('Login ou senha inválidos!');
        }
    }

    public function cadastrar() {
        return ['mensagem' => $this->usuario_dao->gravar() ? 'Usuário cadastrado com sucesso' : 'Não foi possível cadastrar o usuário'];
    }

    public function editar() {
        return ['mensagem' => $this->usuario_dao->editar() ? 'Usuário editado com sucesso' : 'Não foi possível editar o usuário'];
    }

    public function excluir() {
        return ['mensagem' => $this->usuario_dao->excluir() ? 'Usuário excluído com sucesso' : 'Não foi possível excluir o usuário'];
    }

    public function pesquisar() {
        $retorno_pesquisa_usuarios = $this->usuario_dao->pesquisar();
        return ['message' => 'Pesquisa realizada', 'dados' => array_map(function ($usuario_retorno) {
            return [
                'nome' => $usuario_retorno['usua_nome']
            ];
        }, $retorno_pesquisa_usuarios)];
    }
}
