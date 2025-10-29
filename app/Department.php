<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class Department extends Model  implements Auditable
{

    use \OwenIt\Auditing\Auditable;
    //

    public function dep_head()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function permit_account()
    {
        return $this->hasOne(User::class, 'id', 'permit_accountable');
    }
    public function permit_accounts()
    {
        return $this->hasMany(PermitAccountable::class);
    }
    public function dco()
    {
        return $this->hasMany(DepartmentDco::class);
    }
    public function departments()
    {
        return $this->hasMany(UserDepartment::class);
    }
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
    public function obsoletes()
    {
        return $this->hasMany(Obsolete::class);
    }
    public function drc()
    {
        return $this->hasMany(User::class)->where('role','Documents and Records Controller')->where('status',null);
    }
    public function approvers()
    {
        return $this->hasMany(DepartmentApprover::class);
    }
}
