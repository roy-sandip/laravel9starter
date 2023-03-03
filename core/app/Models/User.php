<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Agent;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function IS_SUPER()
    {
        return $this->is_super  && $this->agent_id == Agent::MAIN_BRANCH_ID;
    }

    public function IS_AGENT()
    {
        return $this->agent_id != Agent::MAIN_BRANCH_ID;
    }

    public function adminlte_image()
    {
        return "https://www.gravatar.com/avatar/".md5($this->email)."?s=200&d=mp";
    }

    public function adminlte_desc()
    {
        return 'user description';
    }

    public function adminlte_profile_url()
    {
        return 'admin.home';
    }
}
