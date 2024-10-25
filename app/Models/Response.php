<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'answers'
    ];

    protected $casts = [
        'answers' => 'array'
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
