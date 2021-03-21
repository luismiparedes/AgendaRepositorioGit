<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    
    public function contact(){
    return $this->belongsTo(COntact::class/*,'card_id'*/);
    }
    public function user(){
    return $this->belongsTo(User::class/*,'collection_id'*/);
    }
}
