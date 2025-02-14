@extends('backend.admin-master')
@section('site-title', __('Assign Delivery Man Mail'))
@section('style')
    <x-summernote.css />
@endsection
@section('content')
    <div class="card">
        <div class="row">
            <div class="col-lg-12">
                <div class="card-body">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex d-flex justify-content-between">
                            <h4 class="customMarkup__single__title">{{ __('Assign Delivery Man Mail To User') }}</h4>

                            <a class="btn btn-info" href="{{ route('admin.email-template.email.template.all') }}">{{ __('All Templates') }}</a>
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{ route('admin.email-template.delivery-man.assign-mail-to-user') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <x-form.text
                                    :title="__('Email Subject')"
                                    :type="__('text')" name="assign_delivery_man_mail_to_user_subject" id="assign_delivery_man_mail_subject"
                                    :value="get_static_option('assign_delivery_man_mail_to_user_subject') ?? __('You have assigned for new order')"
                                />

                                <x-form.summernote
                                    :title="__('Email Message')" name="assign_delivery_man_mail_to_user_body" id="assign_delivery_man_mail_body"
                                    :value="get_static_option('assign_delivery_man_mail_to_user_body') ?? '</p>Your refund request successfully submitted.</p><p>We will notify you as soon as possible.</p>'"
                                />

                                <ol class="mt-4">
                                    <li><b>@username</b> {{ __("hare will replace delivery man full name") }}</li>
                                    <li class="mt-2"><b>@{{ "break" }} </b> {{ __("this will break line and create new line") }}</li>
                                    <li class="mt-2"><b>@deliveryManIformation</b> {{ __("hare will replace order information like products information") }}</li>
                                </ol>

                                <button type="submit" class="btn btn-info mt-3">{{ __("Update") }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <x-summernote.js />
@endsection
