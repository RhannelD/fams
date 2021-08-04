<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ScholarResponseFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'response_id',
        'item_id',
        'file_url',
        'file_name',
    ];

    public function if_file_exist()
    {
        return Storage::disk('files')->exists($this->file_url);
    }

    public function get_file_extension()
    {
        return pathinfo($this->file_url, PATHINFO_EXTENSION);
    }

    public function delete_file()
    {
        if ( $this->if_file_exist() ) {
            Storage::disk('files')->delete($this->file_url);
        }
    }

    public function delete()
    {
        $this->delete_file();
        
        return parent::delete();
    }
}
