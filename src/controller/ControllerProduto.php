<?php

namespace App\Controller;

use App\Dao\ProdutoDao;

class ControllerProduto {
    /**
     * @var ProdutoDao
     */
    private ProdutoDao $produto_dao;

    public function __construct(&$produto_dao) {
        $this->produto_dao = $produto_dao;
    }

    public function cadastrar() {
        return ['mensagem' => $this->produto_dao->gravar() ? 'Produto cadastrado com sucesso' : 'Não foi possível cadastrar o produto'];
    }

    public function editar() {
        return ['mensagem' => $this->produto_dao->editar() ? 'Produto editado com sucesso' : 'Não foi possível editar o produto'];
    }

    public function excluir() {
        return ['mensagem' => $this->produto_dao->excluir() ? 'Produto excluído com sucesso' : 'Não foi possível excluir o produto'];
    }

    public function pesquisar() {
        $retorno_pesquisa_produtos = $this->produto_dao->pesquisar();
        return ['message' => 'Pesquisa realizada', 'dados' => array_map(function ($produto_retorno) {
            return [
                'nome' => $produto_retorno['prod_nome'],
                'preco' => $produto_retorno['prod_preco'],
                'quantidade' => $produto_retorno['prod_quantidade'],
                'descricao' => $produto_retorno['prod_descricao'],
            ];
        }, $retorno_pesquisa_produtos)];
    }
}
