<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Http\Requests\StoreProdutoRequest;
use App\Http\Requests\UpdateProdutoRequest;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function __construct(Produto $produto){
        $this->produto = $produto;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Produto::count() < 2){
            return response()->json(['erro' => 'A quantidade total de registros de produtos é menor do que 2'], 500);
        }
        
        if($request->has('filtro')){
            if(strpos($request->filtro, ',')){
                $produtos = explode(',', $request->filtro);
                foreach($produtos as $produto){
                    $this->produto = $this->produto->orWhere('id', $produto);
                }
            }else{
                return response()->json(['erro' => 'Pesquise pelo menos dois produtos'], 400);
            }
        }

        return response()->json($this->produto->get(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProdutoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(strpos($request->nome, ';') && strpos($request->descricao, ';')){
            $nomes = explode(';', $request->nome);
            $descricoes = explode(';', $request->descricao);
            
            if(sizeof($nomes) == sizeof($descricoes)){
                $produtos = array();
                foreach($nomes as $key => $nome){
                    $request->validate($this->produto->rules());
                    $produtos[$key] = $this->produto->create([
                        'nome' => $nome,
                        'descricao' => $descricoes[$key]
                    ]);
                }
                return response()->json($produtos, 200);
            }else{
                return response()->json(['erro' => 'A quantidade de produtos inseridos deve ser a mesma de descrições'], 400);
            }
        }else{
            return response()->json(['erro' => 'Insira pelo menos dois produtos e duas descrições'], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function edit(Produto $produto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProdutoRequest  $request
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $produtos = explode(',', $id);
        $nomes = explode(';', $request->nome);
        $descricoes = explode(';', $request->descricao);
        $msg = array();

        foreach($produtos as $produto){
            if(!$this->produto->find($produto)){
                array_push($msg, $produto);
            }
        }

        if($msg != null){
            if(sizeof($msg) == 1){
                return response()->json(['erro' => 'O produto com id: ' . $msg[0] . ' não pôde ser encontrado, nenhum produto atualizado']);
            }
            $mensagem = '';
            foreach($msg as $m){
                if($msg[count($msg) - 2] == $m){
                    $mensagem = $mensagem . $m . ' e ';
                }else if($msg[count($msg) - 1] == $m){
                    $mensagem = $mensagem . $m;
                }else{
                    $mensagem = $mensagem . $m . ', ';
                }
            }
            return response()->json(['erro' => 'Os produtos com id: ' . $mensagem . ' não puderam ser encontrados, nenhum produto atualizado']);
        }else{
            if(sizeof($nomes) != sizeof($descricoes)){
                return response()->json(['erro' => 'A quantidade de produtos inserida tem que ser igual à quantidade de descrições']);
            }
            if(sizeof($produtos) != sizeof($nomes)){
                return response()->json(['erro' => 'A quantidade de ids passados por parâmetro tem que ser igual à quantidade de nomes e descrições']);
            }
            if(sizeof($produtos) < 2){
                return response()->json(['erro' => 'Pelo menos dois produtos devem ser atualizados']);
            }
        }

        foreach($produtos as $key => $produto){
            $p = $this->produto->find($produto);

            $request->validate($this->produto->rules());
            $p->nome = $nomes[$key];
            $p->descricao = $descricoes[$key];
            $p->save();
        }

        return response()->json('Produtos atualizados com sucesso', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produtos = explode(',', $id);
        $produto = $this->produto->find($id);
        $msg = array();

        foreach($produtos as $produto){
            if(!$this->produto->find($produto)){
                array_push($msg, $produto);
            }
        }

        if($msg != null){
            if(sizeof($msg) == 1){
                return response()->json(['erro' => 'O produto com id: ' . $msg[0] . ' não pôde ser encontrado, nenhum produto deletado']);
            }
            $mensagem = '';
            foreach($msg as $m){
                if($msg[count($msg) - 2] == $m){
                    $mensagem = $mensagem . $m . ' e ';
                }else if($msg[count($msg) - 1] == $m){
                    $mensagem = $mensagem . $m;
                }else{
                    $mensagem = $mensagem . $m . ', ';
                }
            }
            return response()->json(['erro' => 'Os produtos com id: ' . $mensagem . ' não puderam ser encontrados, nenhum produto deletado']);
        }else{
            if(sizeof($produtos) < 2){
                return response()->json(['erro' => 'Pelo menos dois produtos devem ser deletados']);
            }
        }

        foreach($produtos as $produto){
            $p = $this->produto->find($produto);

            $p->delete();
        }

        return response()->json(['msg' => 'Os produtos foram removidos com sucesso'], 200);
    }
}
