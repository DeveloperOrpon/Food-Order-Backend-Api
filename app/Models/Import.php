<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'file_name',
        'file_path',
        'importer',
        'updated_at',
        'created_at',
        'total_rows',
    ];
}
