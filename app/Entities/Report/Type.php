<?php


namespace App\Entities\Report;


use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'report_types';

    protected $fillable = ['name'];

    public function reports()
    {
//        return $this->hasMany(Report::class);
    }
}
