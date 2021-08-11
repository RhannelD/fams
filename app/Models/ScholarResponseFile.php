<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
        $count = DB::table('scholar_response_files')
            ->where('file_url', $this->file_url)
            ->limit(2)
            ->get()
            ->count();
        
        if ($count == 2) {
           return;
        }

        if ( $this->if_file_exist() ) {
            Storage::disk('files')->delete($this->file_url);
        }
    }

    public function delete()
    {
        $this->delete_file();
        
        return parent::delete();
    }
    
    public function response()
    {
        return $this->belongsTo(ScholarResponse::class, 'response_id', 'id');
    }
}
