@extends('backend.admin-master')
@section('site-title')
    {{__('Import Products')}}
@endsection
@section('style')
<link rel="stylesheet" href="{{asset('assets/backend/css/nice-select.css')}}">
<style>
    .nice-select .option {
        min-height: 30px;
        padding: 0px 10px;
        font-size: 14px;
        font-weight: 600;
    }
    .nice-select .option:hover, .nice-select .option.focus, .nice-select .option.selected.focus {
        font-weight: 700;
    }
</style>
@endsection
@section('content')
    <br>
    <div class="col-lg-12 col-ml-12 padding-bottom-30 ">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body mt-50">
                        <x-msg.error/>
                        <x-msg.flash/>

                        <h2 class="title margin-bottom-20">{{__('Import Products')}}</h2>

                        @if(empty($import_data))
                            <form action="{{route('admin.products.import.settings.update')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="#">{{__('File')}}</label>
                                    <input type="file" name="csv_file" accept=".csv" class="form-control" required>
                                    <div class="info-text">{{__('only csv file are allowed with separate by (,) comma.')}}</div>
                                </div>
                                <button type="submit" class="btn btn-info loading-btn">{{__('Submit')}}</button>
                            </form>
                        @else
                            @php
                                $option_markup = '';
                                foreach(current($import_data) as $map_item ){
                                    $option_markup .= '<option value="'.trim($map_item).'">'.$map_item.'</option>';
                                }
                            @endphp
                            <form action="{{route('admin.products.import.to.database')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <table class="table table-striped">
                                    <thead>
                                    <th style="width: 200px">{{{__('Field Name')}}}</th>
                                    <th>{{{__('Set Field')}}}</th>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><h6>{{__('Title')}}</h6></td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control mapping_select wide">
                                                    <option value="">{{__('Select Field')}}</option>
                                                    {!! $option_markup !!}
                                                </select>
                                                <input type="hidden" name="title">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h6>{{__('Short Description')}}</h6></td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control mapping_select wide">
                                                    <option value="">{{__('Select Field')}}</option>
                                                    {!! $option_markup !!}
                                                </select>
                                                <input type="hidden" name="short_description">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h6>{{__('Description')}}</h6></td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control mapping_select wide">
                                                    <option value="">{{__('Select Field')}}</option>
                                                    {!! $option_markup !!}
                                                </select>
                                                <input type="hidden" name="description">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h6>{{__('Badge')}}</h6></td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control mapping_select wide">
                                                    <option value="">{{__('Select Field')}}</option>
                                                    {!! $option_markup !!}
                                                </select>
                                                <input type="hidden" name="badge">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h6>{{__('Slug')}}</h6></td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control mapping_select wide">
                                                    <option value="">{{__('Select Field')}}</option>
                                                    {!! $option_markup !!}
                                                </select>
                                                <input type="hidden" name="slug">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h6>{{__('SKU')}}</h6></td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control mapping_select wide">
                                                    <option value="">{{__('Select Field')}}</option>
                                                    {!! $option_markup !!}
                                                </select>
                                                <input type="hidden" name="sku">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h6>{{__('Stock')}}</h6></td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control mapping_select wide">
                                                    <option value="">{{__('Select Field')}}</option>
                                                    {!! $option_markup !!}
                                                </select>
                                                <input type="hidden" name="stock">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h6>{{__('Total Sales')}}</h6></td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control mapping_select wide">
                                                    <option value="">{{__('Select Field')}}</option>
                                                    {!! $option_markup !!}
                                                </select>
                                                <input type="hidden" name="sales">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h6>{{__('Regular Price')}}</h6></td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control mapping_select wide">
                                                    <option value="">{{__('Select Field')}}</option>
                                                    {!! $option_markup !!}
                                                </select>
                                                <input type="hidden" name="regular_price">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h6>{{__('Sale Price')}}</h6></td>
                                        <td>
                                            <div class="form-group">
                                                <select  class="form-control mapping_select wide">
                                                    <option value="">{{__('Select Field')}}</option>
                                                    {!! $option_markup !!}
                                                </select>
                                                <input type="hidden" name="sale_price">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h6>{{__('Category')}}</h6></td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control mapping_select wide">
                                                    <option value="">{{__('Select Category')}}</option>
                                                    @foreach($category as $cat)
                                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="category_id">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h6>{{__('Subcategory')}}</h6></td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control mapping_select wide">
                                                    <option value="">{{__('Select subcategory')}}</option>
                                                    @foreach($sub_category as $sub_cat)
                                                        <option value="{{$sub_cat->id}}">{{$sub_cat->name}}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="sub_category_id">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h6>{{__('Image')}}</h6></td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control mapping_select wide">
                                                    <option value="">{{__('Select Field')}}</option>
                                                    {!! $option_markup !!}
                                                </select>
                                                <input type="hidden" name="image">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><h6>{{__('Status')}}</h6></td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control mapping_select wide">
                                                    <option value="publish">{{__('Publish')}}</option>
                                                    <option value="draft">{{__('Draft')}}</option>
                                                </select>
                                                <input type="hidden" name="status" value="publish">
                                            </div>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-success loading-btn">{{__('Import')}}</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('assets/backend/js/jquery.nice-select.min.js')}}"></script>
    <script>
        (function ($){
            "use strict";

            $(document).on('click','.loading-btn',function (){
                $(this).append('<i class="ml-2 fas fa-spinner fa-spin"></i>')
            });

            $(document).on('change','.mapping_select',function (){
                let allValue = $('.mapping_select');
                $('.mapping_select option').attr('disabled',false);
                $(this).next('input').val($(this).val());
                $.each(allValue,function (index,item){
                    $('.mapping_select option[value="'+$(this).val()+'"]').attr('disabled',true);
                });
            });

            $('.mapping_select').niceSelect();
        })(jQuery);
    </script>
@endsection



