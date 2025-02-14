<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Support\SupportTicketMessage
 *
 * @property int $id
 * @property string|null $message
 * @property string|null $notify
 * @property string|null $attachment
 * @property string|null $type
 * @property int|null $support_ticket_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereNotify($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereSupportTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupportTicketMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SupportTicketMessage extends Model
{
    use HasFactory;

    protected $table = 'support_ticket_messages';
    protected $fillable = [
        'message',
        'notify',
        'attachment',
        'support_ticket_id',
        'type'
    ];
}
