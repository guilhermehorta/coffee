<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_id', 'role_id', 'period', 'phase', 'report',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'report' => 'object',
    ];

    /**
     * The game the order belongs to.
     */
    public function game()
    {
        return $this->belongsTo('App\Game');
    }

    /**
     * The role the order belongs to.
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }
}
