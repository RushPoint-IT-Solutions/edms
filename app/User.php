<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Auditable
{
    use Notifiable;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role'
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
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function department_head()
    {
        return $this->hasMany(Department::class,'user_id','id');
    }
    public function permits()
    {
        return $this->hasMany(Department::class,'id','permit_accountable');
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function dco()
    {
        return $this->hasMany(DepartmentDco::class);
    }
    public function accountable_persons()
    {
        return $this->hasMany(PermitAccountable::class);
    }
    public function department_approvers()
    {
        return $this->hasMany(DepartmentApprover::class);
    }
    public function copy_approvers()
    {
        return $this->hasMany(CopyApprover::class);
    }
    public function change_approvers()
    {
        return $this->hasMany(RequestApprover::class);
    }
    public function departments()
    {
        return $this->hasMany(UserDepartment::class);
    }
}
