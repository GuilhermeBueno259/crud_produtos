<?php
namespace App\Dao;

use App\Modelo\Conexao;
use App\modelo\Usuario;

class UsuarioDao {
    private Usuario $usuario;

    private Conexao $conexao;

    public function __construct(&$conexao, &$usuario) {
        $this->conexao = $conexao;
        $this->usuario = $usuario;
    }

    public function gravar() {
        $valores_usuario = $this->usuario->retornarValores();

        $array_campos_insercao = [];
        foreach (array_keys($valores_usuario) as $campo_insercao) {
            $array_campos_insercao[] = $campo_insercao;
        }

        $operacao = "INSERT INTO usuario (" . implode(", ", $array_campos_insercao) . ") VALUES (:" . implode(", :", $array_campos_insercao). ");";
        return $this->conexao->executar($operacao, $valores_usuario);
    }

    public function editar() {
    }

    public function deletar() {
    }

    public function pesquisar() {
        $valores_usuario = $this->usuario->retornarValores();

        if (empty($valores_usuario)) {
            $consulta = "SELECT * FROM usuario";
            return $this->conexao->executar($consulta);
        } else {
            $campos_valores_usuario = array_keys($valores_usuario);

            $string_campos_valor_usuario = '';

            foreach ($campos_valores_usuario as $posicao => $campo_valor_usuario) {
                if ($posicao == 0) {
                    $string_campos_valor_usuario .= " {$campo_valor_usuario} = :{$campo_valor_usuario}";
                } else {
                    $string_campos_valor_usuario .= " AND {$campo_valor_usuario} = :{$campo_valor_usuario}";
                }
            }

            $consulta = "SELECT * FROM usuario WHERE{$string_campos_valor_usuario}";
            return $this->conexao->executar($consulta, $valores_usuario);
        }
    }
}
