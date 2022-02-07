<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAlbums extends Model {
    
    public $timestamps = false;
    
    protected $table;
    
    protected $fillable = ['user_id', 'en_albums_full_access', 'en_albums_limited_access', 'ru_albums_full_access', 'ru_albums_limited_access'];
    
    public function user() {
        return $this->belongsTo('App\User');
    }
}
