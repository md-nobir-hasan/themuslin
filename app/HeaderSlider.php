<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\HeaderSlider
 *
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderSlider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderSlider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HeaderSlider query()
 * @mixin \Eloquent
 */
class HeaderSlider extends Model
{
    use HasFactory;
    protected $table = 'header_sliders';
    protected $fillable = ['title','subtitle','description','btn_01_status','btn_01_text','btn_01_url','image'];

}
