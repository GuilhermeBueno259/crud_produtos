<?php

namespace App\Dao;

use App\Modelo\Conexao;
use App\modelo\Usuario;

class UsuarioDao {
    public Usuario $usuario;

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

        $operacao = "INSERT INTO usuario (" . implode(", ", $array_campos_insercao) . ") VALUES (:" . implode(", :", $array_campos_insercao) . ");";
        return $this->conexao->executar($operacao, $valores_usuario);
    }

    public function editar() {
        $valores_usuario = $this->usuario->retornarValores();

        $array_campos_atualizacao = [];
        foreach (array_keys($valores_usuario) as $campo_atualizacao) {
            if ($campo_atualizacao != 'usua_codigo') {
                $array_campos_atualizacao[] = "{$campo_atualizacao} = :{$campo_atualizacao}";
            }
        }

        $operacao = "UPDATE usuario SET " . implode(', ', $array_campos_atualizacao) . " WHERE usua_codigo = :usua_codigo";
        return $this->conexao->executar($operacao, $valores_usuario);
    }

    public function excluir() {
        $operacao = "DELETE FROM usuario WHERE usua_codigo = :usua_codigo";
        return $this->conexao->executar($operacao, ['usua_codigo' => $this->usuario->usua_codigo]);
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
