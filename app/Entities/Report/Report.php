<?php


namespace App\Entities\Report;


use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'user_reports';

    protected $fillable = [
        'denunciator_id',
        'reported_id',
        'type_id',
        'description'
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
