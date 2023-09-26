<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'mt_document';

    protected $fillable = [
        'name',
        'link',
        'type',
        'content',
        'mime_type',
        'user_id',
        'user_type',
        'created_at',
        'updated_at',
    ];
}
