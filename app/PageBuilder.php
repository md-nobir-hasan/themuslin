<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PageBuilder
 *
 * @property int $id
 * @property string|null $addon_name
 * @property string|null $addon_type
 * @property string|null $addon_location
 * @property int|null $addon_order
 * @property int|null $addon_page_id
 * @property string|null $addon_page_type
 * @property string|null $addon_settings
 * @property string|null $addon_namespace
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PageBuilder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageBuilder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageBuilder query()
 * @method static \Illuminate\Database\Eloquent\Builder|PageBuilder whereAddonLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBuilder whereAddonName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBuilder whereAddonNamespace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBuilder whereAddonOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBuilder whereAddonPageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBuilder whereAddonPageType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBuilder whereAddonSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBuilder whereAddonType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBuilder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBuilder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBuilder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PageBuilder extends Model
{
    protected $table = 'page_builders';
    protected $fillable = [
      'addon_name',
      'addon_type',
      'addon_location',
      'addon_order',
      'addon_page_id',
      'addon_page_type',
      'addon_settings',
      'addon_namespace',
    ];
}
