<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'judul','penulis','penerbit','tahun_terbit','user_id'
    ];

   /**
     * A post belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}