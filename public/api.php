<?php
require_once __DIR__ . '/../vendor/autoload.php';

header('Content-type: application/json');

use App\Controller\ControllerUsuario;
use App\Dao\UsuarioDao;
use App\Excessoes\ExcessaoDadosInvalidos;
use App\Modelo\Conexao;
use App\Modelo\Usuario;

$caminho = $_SERVER['PATH_INFO'];
$dados_requisicao = json_decode(file_get_contents('php://input'), true);
$conexao = new Conexao();

try {
    if ($caminho != '/api/autenticar') {
        $cabecalhos_requisicao = getallheaders();

        if (empty($cabecalhos_requisicao['Authorization'])) {
            throw new ExcessaoDadosInvalidos("O token obtido na autenticação deve ser passado no cabeçalho Authorization");
        }

        $usuario = new Usuario();
        $usuario->usua_token = explode(' ', $cabecalhos_requisicao['Authorization'])[1];
        $usuario_dao = new UsuarioDao($conexao, $usuario);
        $resultado = $usuario_dao->pesquisar();
        if (count($resultado) <= 0 || strtotime($resultado[0]['usua_expiracao_token']) < time()) {
            throw new ExcessaoDadosInvalidos("Token inválido");
        }
    }

    switch ($caminho) {
        case '/api/autenticar':
            $usuario = new Usuario();
            $usuario->usua_login = $dados_requisicao['login'];
            $usuario->usua_senha = $dados_requisicao['senha'];
            $usuario_dao = new UsuarioDao($conexao, $usuario);
            $controller_usuario = new ControllerUsuario($usuario_dao);
            $retorno = $controller_usuario->autenticar();
            break;
        case '/api/usuario/cadastrar':
            $usuario = new Usuario();
            $usuario->usua_nome = $dados_requisicao['nome'];
            $usuario->usua_login = $dados_requisicao['login'];
            $usuario->usua_senha = $dados_requisicao['senha'];
            $usuario_dao = new UsuarioDao($conexao, $usuario);
            $controller_usuario = new ControllerUsuario($usuario_dao);
            $retorno = $controller_usuario->cadastrar();
            break;
        case '/api/usuario/atualizar':
            $usuario = new Usuario();
            $usuario->usua_codigo = $dados_requisicao['codigo'];
            $usuario->usua_nome = $dados_requisicao['nome'];
            $usuario->usua_login = $dados_requisicao['login'];
            $usuario->usua_senha = $dados_requisicao['senha'];
            $usuario_dao = new UsuarioDao($conexao, $usuario);
            $controller_usuario = new ControllerUsuario($usuario_dao);
            $retorno = $controller_usuario->editar();
            break;
        case '/api/usuario/excluir':
            $usuario = new Usuario();
            $usuario->usua_codigo = $dados_requisicao['codigo'];
            $usuario_dao = new UsuarioDao($conexao, $usuario);
            $controller_usuario = new ControllerUsuario($usuario_dao);
            $retorno = $controller_usuario->excluir();
            break;
        case '/api/usuario/pesquisar':
            $usuario = new Usuario();
            if (isset($_GET['codigo']))
                $usuario->usua_codigo = $_GET['codigo'];

            if (isset($_GET['nome']))
                $usuario->usua_nome = $_GET['nome'];

            $usuario_dao = new UsuarioDao($conexao, $usuario);
            $controller_usuario = new ControllerUsuario($usuario_dao);
            $retorno = $controller_usuario->pesquisar();
            break;
        default:
            throw new ExcessaoDadosInvalidos("O método \"{$caminho}\" não existe");
            break;
    }

    http_response_code(200);
    die(json_encode($retorno));
} catch (ExcessaoDadosInvalidos $excessao_dados_invalidos) {
    http_response_code(400);
    die(json_encode(['mensagem' => $excessao_dados_invalidos->getMessage()]));
} catch (Exception $excessao) {
    http_response_code(500);
    die(json_encode(['mensagem' => $excessao->getMessage()]));
}
