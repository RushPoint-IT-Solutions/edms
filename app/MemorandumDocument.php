<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemorandumDocument extends Model
{
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    public function memorandum()
    {
        return $this->belongsTo(Memorandum::class);
    }
}
