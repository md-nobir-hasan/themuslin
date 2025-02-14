<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Widgets
 *
 * @property int $id
 * @property string|null $widget_name
 * @property string $widget_content
 * @property int|null $widget_order
 * @property string|null $widget_location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Widgets newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Widgets newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Widgets query()
 * @method static \Illuminate\Database\Eloquent\Builder|Widgets whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widgets whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widgets whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widgets whereWidgetContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widgets whereWidgetLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widgets whereWidgetName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Widgets whereWidgetOrder($value)
 * @mixin \Eloquent
 */
class Widgets extends Model
{
    protected $table = 'widgets';
    protected $fillable = ['widget_area','widget_order','widget_name','widget_content','widget_location'];
}
