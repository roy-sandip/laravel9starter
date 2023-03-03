<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    public static function boot() 
      {
            parent::boot();
      
            /**
             * Write code on Method
             *
             * @return response()
             */
            static::creating(function($item) {            
                
            });
      
            
      }


    protected function deleteFile()
    {
        if(Storage::disk($this->disk)->exists($this->path))
        {
            Storage::disk($this->disk)->delete($this->path);
        }
    }

    public function download()
    {
        return Storage::disk($this->disk)->download($this->path, $this->name);
    }


    public function upload($file, $directory = null)
    {
        if(!isset($this->disk))
        {
            $this->disk = 'public';
        }
        $path =  Storage::disk($this->disk)->put($directory, $file);
        $this->path = $path;
        $this->type = Storage::disk($this->disk)->mimeType($path);
        $this->name = $file->getClientOriginalName();
        return $this;
    }

    public function disk($disk)
    {
        $this->disk = $disk;
        return $this;
    }

    public function delete()
    {
        $this->deleteFile();
        return parent::delete();
    }

    public function link()
    {
        return isset($this->id) ? route('admin.media.download', $this->id) : null;
    }


}
