<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Support\SupportDepartment
 *
 * @property int $id
 * @property string $name
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDepartment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDepartment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDepartment query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDepartment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDepartment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDepartment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDepartment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportDepartment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SupportDepartment extends Model
{
    use HasFactory;

    protected $table = 'support_departments';
    protected $fillable = [
        'name',
        'status'
    ];
}
