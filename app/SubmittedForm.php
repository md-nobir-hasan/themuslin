<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\FormBuilder
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $email
 * @property string|null $button_text
 * @property string|null $fields
 * @property string|null $success_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class SubmittedForm extends Model
{
    use HasFactory;

    protected $table = 'submitted_forms';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'message',
        'status'
    ];
}
