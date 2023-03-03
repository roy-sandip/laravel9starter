<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Media;

class MediaPivot extends Model
{
    use HasFactory;
    protected $fillable = ['model', 'model_id', 'media_id'];
    
    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
