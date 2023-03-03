<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ShipmentPermissionScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $user = auth()->user();

        //super user can do all
        if($user->IS_SUPER())
        {
            return true;
        }

        
        
        //agent user can view all under the agent_id
        if($user->IS_AGENT())
        {
            return $builder->where('agent_id', '=', $user->agent_id);
        }

        //general user can view self records
        return $builder->where('user_id', '=', $user->id);  
    }
    
}
