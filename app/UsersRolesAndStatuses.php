<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersRolesAndStatuses extends Model
{
    public $timestamps = false;
    
    protected $table;
    
    protected $fillable = ['role', 'status'];
    
    public function user() {
        return $this->belongsTo('App\User');
    }
}
