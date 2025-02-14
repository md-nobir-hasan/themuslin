<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Comment
 *
 * @property int $id
 * @property int|null $cause_id
 * @property int|null $user_id
 * @property string|null $commented_by
 * @property string $comment_content
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCauseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
 * @mixin \Eloquent
 */
class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['cause_id','user_id','commented_by','comment_content'];
    protected $table = 'comments';

    public function cause(){
        return $this->belongsTo(Cause::class,);
    }

    public function user(){
        return $this->belongsTo(User::class,);
    }
}
