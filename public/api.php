<?php
require_once __DIR__ . '/../vendor/autoload.php';

header('Content-type: application/json');

use App\Controller\ControllerUsuario;
use App\Dao\UsuarioDao;
use App\Modelo\Conexao;
use App\Modelo\Usuario;

$caminho = $_SERVER['REQUEST_URI'];
$dados_requisicao = json_decode(file_get_contents('php://input'), true);
$conexao = new Conexao();

switch ($caminho) {
    case '/api/autenticar':
        $usuario = new Usuario();
        $usuario->usua_login = $dados_requisicao['login'];
        $usuario->usua_senha = $dados_requisicao['senha'];
        $usuario_dao = new UsuarioDao($conexao, $usuario);
        $controller_usuario = new ControllerUsuario($usuario_dao);
        $controller_usuario->entrar();
        break;
    case '/api/usuario/cadastrar':
        $usuario = new Usuario();
        $usuario->usua_nome = $dados_requisicao['nome'];
        $usuario->usua_login = $dados_requisicao['login'];
        $usuario->usua_senha = $dados_requisicao['senha'];
        $usuario_dao = new UsuarioDao($conexao, $usuario);
        $controller_usuario = new ControllerUsuario($usuario_dao);
        $controller_usuario->cadastrar();
    default:
        # code...
        break;
}
