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
 * @method static \Illuminate\Database\Eloquent\Builder|FormBuilder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormBuilder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FormBuilder query()
 * @method static \Illuminate\Database\Eloquent\Builder|FormBuilder whereButtonText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormBuilder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormBuilder whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormBuilder whereFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormBuilder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormBuilder whereSuccessMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormBuilder whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FormBuilder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FormBuilder extends Model
{
    use HasFactory;

    protected $table = 'form_builders';
    protected $fillable = [
        'title',
        'email',
        'button_text',
        'fields',
        'success_message'
    ];
}
