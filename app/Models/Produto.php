<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'descricao'];

    public function rules(){
        return [
            'nome' => 'required',
            'descricao' => 'required'
        ];
    }
}
