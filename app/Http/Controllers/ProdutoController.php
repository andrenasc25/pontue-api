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
        $produto = $this->produto->find($id);
        if($produto === null){
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }
        return response()->json($produto, 200);
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
        $produto = $this->produto->find($id);

        if($produto === null){
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();
            foreach($produto->rules() as $input => $regra){
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        }else{
            $request->validate($produto->rules());
        }

        $produto->fill($request->all());
        $produto->save();

        return response()->json($produto, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produto $produto)
    {
        $produto = $this->produto->find($id);
        if($produto === null){
            return response()->json(['erro' => 'O produto solicitado não existe'], 404);
        }
        $produto->delete();
        return response()->json(['msg' => 'O produto foi removido com sucesso'], 200);
    }
}
