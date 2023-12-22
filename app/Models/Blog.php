<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Likeable;

class Blog extends Model
{
    use HasFactory;
    use Likeable;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'discription',
        'image'
    ];

    public function isLikedByUser($userId)
    {
        return $this->likes()->where(['user_id'=> $userId, 'reaction'=>1])->exists();
    }

}
