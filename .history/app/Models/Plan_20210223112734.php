<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'url', 'price', 'description'];


    public function search($filter = null)
    {
        $results = $this->where('name', 'LIKE', "%{$filter}%")
            ->orWhere('description', 'LIKE', "%{$filter}%")
            ->paginate();

        return $results;
    }

    //Relacionamento com Tabela Detalhes do Plano
    public function details()
    {
        //hasMany => Um para Muitos
        return $this->hasMany(DetailPlan::class);
    }


    //Relacionamento com Tabela Profiles
    public function profiles()
    {
        //belongsToMany => Muitos para Muitos
        return $this->belongsToMany(Profile::class);
    }
}
