<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $table = 'tags';
    protected $fillable = ['name', 'created_by'];
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
    