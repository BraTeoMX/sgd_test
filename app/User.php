<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Propaganistas\LaravelFakeId\RoutesWithFakeIds;
use App\Notifications\RecuperarPassword;

class User extends Authenticatable
{
    use Notifiable, HasRoles, RoutesWithFakeIds;

    protected $guard_name = 'web';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
         'name',
         'email',
         'password',
         'puesto',
         'no_empleado',
         //'sucursal_id',
         //'bodega_id',
         'inactivo',
         'remember_token',
         'fecha_ultimo_acceso',
         'fecha_ultima_notificacion',
         'usuario_creacion',
         'usuario_actualizacion',
     ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'fecha_ultima_notificacion' => 'datetime',
        'fecha_ultimo_acceso' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new RecuperarPassword($token));
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function getIsRolSuperiorAttribute()
    {
        $rol = Role::where('id', '<', auth()->user()->roles()->first()->id)->first();
        return (filled($rol));
    }

    public function getarregloEstatusAttribute(){
        return $estatus=[
            'Todas'=>' Todas',
            'Activas'=>' Activas',
            'Inactivas'=>' Inactivas'
        ];
    }

}
