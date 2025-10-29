<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class UserDepartment extends Model  implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;

    public function dep()
    {
        return $this->belongsTo(Department::class,'department_id','id');
    }
}
