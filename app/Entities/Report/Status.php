<?php


namespace App\Entities\Report;


use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'report_status';

    protected $fillable = ['name'];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
