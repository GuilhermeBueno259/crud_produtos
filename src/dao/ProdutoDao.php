<?php

namespace App\Dao;

use App\Modelo\Conexao;
use App\modelo\Produto;

class ProdutoDao {
    public Produto $produto;

    private Conexao $conexao;

    public function __construct(&$conexao, &$produto) {
        $this->conexao = $conexao;
        $this->produto = $produto;
    }

    public function gravar() {
        $valores_produto = $this->produto->retornarValores();

        $array_campos_insercao = [];
        foreach (array_keys($valores_produto) as $campo_insercao) {
            $array_campos_insercao[] = $campo_insercao;
        }

        $operacao = "INSERT INTO produto (" . implode(", ", $array_campos_insercao) . ") VALUES (:" . implode(", :", $array_campos_insercao) . ");";
        return $this->conexao->executar($operacao, $valores_produto);
    }

    public function editar() {
        $valores_produto = $this->produto->retornarValores();

        $array_campos_atualizacao = [];
        foreach (array_keys($valores_produto) as $campo_atualizacao) {
            if ($campo_atualizacao != 'prod_codigo') {
                $array_campos_atualizacao[] = "{$campo_atualizacao} = :{$campo_atualizacao}";
            }
        }

        $operacao = "UPDATE produto SET " . implode(', ', $array_campos_atualizacao) . " WHERE prod_codigo = :prod_codigo";
        return $this->conexao->executar($operacao, $valores_produto);
    }

    public function excluir() {
        $operacao = "DELETE FROM produto WHERE prod_codigo = :prod_codigo";
        return $this->conexao->executar($operacao, ['prod_codigo' => $this->produto->prod_codigo]);
    }

    public function pesquisar() {
        $valores_produto = $this->produto->retornarValores();

        if (empty($valores_produto)) {
            $consulta = "SELECT * FROM produto";
            return $this->conexao->executar($consulta);
        } else {
            $campos_valores_produto = array_keys($valores_produto);

            $string_campos_valor_produto = '';

            foreach ($campos_valores_produto as $posicao => $campo_valor_produto) {
                if ($posicao == 0) {
                    $string_campos_valor_produto .= " {$campo_valor_produto} = :{$campo_valor_produto}";
                } else {
                    $string_campos_valor_produto .= " AND {$campo_valor_produto} = :{$campo_valor_produto}";
                }
            }

            $consulta = "SELECT * FROM produto WHERE{$string_campos_valor_produto}";
            return $this->conexao->executar($consulta, $valores_produto);
        }
    }
}
