@extends('muslin.layout')
@section('title', 'FAQ')

@section('style')
    <style>
        button.btn.btn-link {
            font-family: auto !important;
        }
    </style>
@endsection

@section('content')

    <!-- breadcrumb section start -->
    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul>
                        <li>
                            <a href="{{ route('homepage') }}">Home</a>
                        </li>
                        <li>FAQs</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb section end -->

    <!-- profile information start -->
    <section class="account-information faqs-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="subtitle">
                        <h2>Frequently Asked Questions</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Tab Items (4 columns) -->
                <div class="col-md-4">
                    <div class="nav flex-column nav-pills show-in-desktop" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">

                        @if (!empty($group))
                            @foreach ($group as $key => $value)
                                <a class="nav-link {{ $key == 0 ? 'active' : '' }}" id="tab1-tab" data-toggle="pill"
                                    href="#tab{{ $key }}" role="tab" aria-controls="tab1"
                                    aria-selected="true">{{ $value }}</a>
                            @endforeach
                        @endif

                    </div>
                    <div class="show-in-mobile Select">
                        <select id="tabSelect">
                            @if (!empty($group))
                                @foreach ($group as $key => $value)
                                    <option value="tab{{ $key }}" {{ $key == 0 ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <!-- Tab Content (8 columns) -->
                <div class="col-md-8">
                    <div class="tab-content" id="v-pills-tabContent">

                        @if (!empty($group))
                            @foreach ($group as $key => $value)
                                <div class="tab-pane fade show {{ $key == 0 ? 'active' : '' }}" id="tab{{ $key }}"
                                    role="tabpanel">

                                    <!-- Content for Tab 1 -->
                                    <div id="accordion">

                                        <?php
                                        $i = 0;
                                        ?>

                                        @foreach ($data as $key_2 => $faq)
                                            @if ($faq->faq_group == $value)
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="mb-0">
                                                            <button style="font-family: auto;"
                                                                class="btn btn-link {{ $key_2 == 0 ? '' : 'collapsed' }}"
                                                                data-toggle="collapse"
                                                                data-target="#collapse{{ $key_2 }}"
                                                                aria-expanded="true"
                                                                aria-controls="collapse{{ $key_2 }}">
                                                                {{ $faq->title }} </button>
                                                        </h5>

                                                        <span data-toggle="collapse"
                                                            data-target="#collapse{{ $key_2 }}" aria-expanded="true"
                                                            aria-controls="collapse{{ $key_2 }}"
                                                            class="bg-black-icon  {{ $i == 0 ? '' : ' collapsed' }}">
                                                            <svg width="14" height="8" viewBox="0 0 14 8"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M1 7L7 1L13 7" stroke="white"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </span>

                                                    </div>
                                                    <div id="collapse{{ $key_2 }}"
                                                        class="collapse {{ $i == 0 ? 'show' : '' }}"
                                                        aria-labelledby="headingOne1" data-parent="#accordion">
                                                        <div class="card-body">
                                                            {!! $faq->description !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php
                                                $i++;
                                                ?>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
