<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outfit extends Model
{
    use HasFactory;

    public function masterOfOutfit()
    {
        // sitas outfitas priklauso app\models\Master pagal rysi master id
        return $this->belongsTo('App\Models\Master', 'master_id', 'id');
    }
 
}
