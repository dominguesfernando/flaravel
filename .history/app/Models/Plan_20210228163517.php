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
        //hasMany => 1:n
        return $this->hasMany(DetailPlan::class);
    }


    //Relacionamento com Tabela Profiles
    public function profiles()
    {
        //belongsToMany => n:n
        return $this->belongsToMany(Profile::class);
    }


    //Busca os Perfils vinculados ao Plano
    public function profilesAvailable($filter = null)
    {
        $profiles = Plan::whereNotIn('profiles.id', function ($query) {
            $query->select('plan_profile.profile_id');
            $query->from('plan_profile');
            $query->whereRaw("plan_profile.plan_id={$this->id}");
        })->where(function ($queryFilter) use ($filter) {
            if ($queryFilter) {
                $queryFilter->where('profiles.name', 'LIKE', "%{$filter}%");
            }
        })->paginate();

        return $profiles;
    }
}
