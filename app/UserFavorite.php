<?php
namespace ThaoHR;

use Illuminate\Database\Eloquent\Model;

class UserFavorite extends  Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users_favorites';
    
    protected $fillable = ['user_id', 'liked_user_id'];
}