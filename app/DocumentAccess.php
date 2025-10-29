<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class DocumentAccess extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;

    public function attachment()
    {
        return $this->belongsTo(DocumentAttachment::class,'attachment_id','id');
    }
}
