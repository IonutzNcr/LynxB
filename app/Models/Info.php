<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class Info extends Model
{
    use HasFactory;
    protected $fillable = ['title','time','date'];

    public $timestamps = false;

    //localScope pour filtrer des requetes pour les events (navré pour le nom)

    public function scopeRat(Builder $query, String $date_begin, String $date_end): void
    {
        $query->whereRaw('UNIX_TIMESTAMP(date)  >= ? AND UNIX_TIMESTAMP(date)  <= ?  ', [Carbon::createFromFormat('Y-m-d', $date_begin)->timestamp, Carbon::createFromFormat('Y-m-d', $date_end)->timestamp]);
    }

}
