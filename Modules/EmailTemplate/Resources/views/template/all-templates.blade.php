@extends('backend.admin-master')
@section('style')
    <style>
        .customMarkup__tab ul {
            display: flex;
            align-items: center;
            gap: 12px 0;
            flex-wrap: wrap;
        }
        .customMarkup__tab ul li {
            font-size: 16px;
            font-weight: 400;
            line-height: 24px;
            background-color: #d9d9d9;
            padding: 8px 15px;
            color: var(--heading-color);
            transition: all .2s;
        }
        .customMarkup__tab ul li.active {
            background-color: var(--customer-profile);
            color: #fff;
        }
    </style>
@endsection

@section('site-title', __('All Templates'))

@php
    $routes = [
        [
            "route" => route("admin.email-template.refund.request-send"),
            "title" => "request Send",
            "description" => __('This email will send when a new refund request created.'),
            "permission" => 'email-template-refund-request-send'
        ],
        [
            "route" => route("admin.email-template.refund.request-approved"),
            "title" => "refund Request Approved",
            "description" => __('This email will send when a new refund request created.'),
            "permission" => 'email-template-refund-request-approve'
        ],
        [
            "route" => route("admin.email-template.refund.request-declined"),
            "title" => "refund Request Declined",
            "description" => __('This email will send when a new refund request created.'),
            "permission" => 'email-template-refund-request-declined'
        ],
        [
            "route" => route("admin.email-template.refund.request-cancel"),
            "title" => "refund Request cancel",
            "description" => __('This email will send when a new refund request created.'),
            "permission" => 'email-template-refund-request-cancel'
        ],
        [
            "route" => route("admin.email-template.refund.request-ready-for-pickup"),
            "title" => "refund Request Ready For Pickup",
            "description" => __('This email will send when a new refund request created.'),
            "permission" => 'email-template-refund-request-ready-for-pickup'
        ],
        [
            "route" => route("admin.email-template.refund.request-picked-up"),
            "title" => "refund Request PickedUp",
            "description" => __('This email will send when a new refund request created.'),
            "permission" => 'email-template-refund-request-picked-up'
        ],
        [
            "route" => route("admin.email-template.refund.request-on-the-way"),
            "title" => "refund Request On The Way",
            "description" => __('This email will send when a new refund request created.'),
            "permission" => 'email-template-refund-request-on-the-way'
        ],
        [
            "route" => route("admin.email-template.refund.request-returned"),
            "title" => "refund Request Returned",
            "description" => __('This email will send when a new refund request created.'),
            "permission" => 'email-template-refund-request-returned'
        ],
        [
            "route" => route("admin.email-template.refund.refund-request-verify-product"),
            "title" => "refund Request Verify Product",
            "description" => __('This email will send when a new refund request created.'),
            "permission" => 'email-template-refund-request-verify-product'
        ],
        [
            "route" => route("admin.email-template.refund.refund-request-payment-processing"),
            "title" => "refund Request Payment Processing",
            "description" => __('This email will send when a new refund request created.'),
            "permission" => 'email-template-refund-request-payment-processing'
        ],
        [
            "route" => route("admin.email-template.refund.refund-request-payment-transferred"),
            "title" => "refund Request Payment Transferred",
            "description" => __('This email will send when a new refund request created.'),
            "permission" => 'email-template-refund-request-payment-transferred'
        ],
        [
            "route" => route("admin.email-template.refund.refund-request-payment-completed"),
            "title" => "refund Request Payment Completed",
            "description" => __('This email will send when a new refund request created.'),
            "permission" => 'email-template-refund-request-payment-completed'
        ],
    ];

    $deliveryManRoutes = [
        [
            "route" => route("admin.email-template.delivery-man.assign-mail"),
            "title" => "Assign delivery man mail",
            "description" => __('This email will send when a delivery man is assigned.'),
            "permission" => 'email-template-delivery-man-assign-mail'
        ],
        [
            "route" => route("admin.email-template.delivery-man.assign-mail-to-user"),
            "title" => "Assign delivery man mail",
            "description" => __('This email will send when a delivery man is assigned.'),
            "permission" => 'email-template-delivery-man-assign-mail-to-user'
        ],
    ];
@endphp

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __("All Templates") }}</h4>
                    <div class="customMarkup__tab">
                        <ul class="tabs">
                            <li data-tab="refund_mail" class="active">{{ __("Refund Mail") }}</li>
                            <li data-tab="delivery_man_mail">{{ __("Delivery Man Mail") }}</li>
                        </ul>
                    </div>
                </div>
                <div class="dashboard__card__body mt-4">
                    <!-- Tab Start -->
                    <div class="tab-content-item active" id="refund_mail">
                        <div class="dashboard__card">
                            <div class="dashboard__card__header">
                                <h4 class="dashboard__card__title">{{ __("Refund Mail List") }}</h4>
                                <x-bulk-action.bulk-action/>
                            </div>
                            <div class="dashboard__card__body mt-4">
                                <div class="custom_table style-04 search_result">
                                    <x-emailtemplate::tamplates.tamplate-table :routes="$routes" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content-item" id="delivery_man_mail">
                        <div class="dashboard__card">
                            <div class="dashboard__card__header">
                                <h4 class="dashboard__card__title">{{ __("Delivery Man Mail List") }}</h4>
                                <x-bulk-action.bulk-action/>
                            </div>
                            <div class="dashboard__card__body mt-4">
                                <div class="custom_table style-04 search_result">
                                    <x-emailtemplate::tamplates.tamplate-table :routes="$deliveryManRoutes" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Tab End -->
                </div>
            </div>
        </div>
    </div>
@endsection
