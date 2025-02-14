<?php

namespace App\Support;

use App\Admin;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Support\SupportTicket
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $via
 * @property string|null $operating_system
 * @property string|null $user_agent
 * @property string|null $description
 * @property string|null $subject
 * @property string|null $status
 * @property string|null $priority
 * @property int|null $departments
 * @property int|null $user_id
 * @property int|null $admin_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Admin|null $admin
 * @property-read \App\Support\SupportDepartment|null $department
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket whereDepartments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket whereOperatingSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicket whereVia($value)
 * @mixin \Eloquent
 */
class SupportTicket extends Model
{
    use HasFactory;

    protected $table = 'support_tickets';
    protected $fillable = [
        'title',
        'via',
        'operating_system',
        'user_agent',
        'description',
        'subject',
        'status',
        'priority',
        'user_id',
        'admin_id',
        'departments',
        'vendor_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(SupportDepartment::class, 'departments', 'id');
    }
}
