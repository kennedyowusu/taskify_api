<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Taskify extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'is_completed'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function complete()
    {
        $this->is_completed = true;
        $this->save();
    }
}
