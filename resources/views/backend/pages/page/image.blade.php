@extends('backend.admin-master')
@section('site-title')
    {{ __('Edit Page') }}
@endsection


<style type="text/css">
	.form-control { 
		padding: 0 10px 0 10px;
	}
</style>

@section('content')

<div class="col-lg-12 col-ml-12">
    <div class="row">
        <div class="col-lg-6">
            <x-msg.error />
            <x-msg.flash />
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __('Upload Page Image') }}</h4>
                    <a href="{{ route('admin.page.edit', $page_post->id) }}" class="cmn_btn btn_bg_profile">{{ __('Update Page') }}</a>
                </div>
                <div class="dashboard__card__body custom__form mt-4">
                    <form action="{{ route('admin.page.image.upload', $page_post->id) }}" method="post"
                        enctype="multipart/form-data">

                        @csrf

                        <div class="row">
                            <div class="col-lg-12">


                            	<div class="custom-file mb-10" style="margin-bottom: 20px;">

									<!--begin::Label-->
									<label class="d-flex align-items-center fs-6 fw-semibold mb-2">
										<span class="">Select Files </span>
									</label>

									<input type="file" class="custom-file-input" id="customFile" name="Images[]"  multiple="" required="">

								</div>	
								<div class="d-flex flex-column mb-3 fv-row">
									<!--begin::Label-->
									<label class="d-flex align-items-center fs-6 fw-semibold mb-2">
										<span class="">Title</span>
									</label>
									<!--end::Label-->
									<input type="text" class="form-control form-control-solid" placeholder="Enter Title" name="title_text" />
								</div>


								<div class="d-flex flex-column mb-3 fv-row">
									<!--begin::Label-->
									<label class="d-flex align-items-center fs-6 fw-semibold mb-2">
										<span class="">Device</span>
									</label>
									<!--end::Label-->
									<select class="form-control" name="device" required>
										<option value="desktop">Desktop</option>
										<option value="mobile"> Mobile</option>
									</select>
								</div>

								<br>
								<br>


								<div class="form-group">
                                    <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Upload Image') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="col-lg-12 col-ml-12" style="margin-top: 40px">
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">Images</h4>
                </div>
                <div class="dashboard__card__body custom__form mt-4">

                        @csrf

                        <div class="row">
                            <div class="col-lg-12">
                            	@if($page_post->images)	
                            		@foreach($page_post->images as $image)

                            			<div class="row" style="margin-bottom: 30px;">
                            				<div class="col-lg-4">
                            					<img src="{{ asset('assets/uploads/media-uploader/page/' . $image->path) }}" class="image-responsive" width="500px">
                            				</div>
                            				<div class="col-lg-8">
                    							<form action="{{ route('admin.page.image.edit', $image->id) }}" method="post">
                    								@csrf
	                            					<div class="row">
	                            						<div class="col-md-4">
	                            							<div class="d-flex flex-column mb-3 fv-row">
																<!--begin::Label-->
																<label class="d-flex align-items-center fs-6 fw-semibold mb-2">
																	<span class="">Title</span>
																</label>
																<!--end::Label-->
																<input type="text" class="form-control form-control-solid" placeholder="Enter Title" name="title_text" value="{{ $image->title_text }}"style="padding: 0 15px;" />
															</div>
	                            						</div>
	                            						<div class="col-md-4">
	                            							<div class="d-flex flex-column mb-3 fv-row">
																<!--begin::Label-->
																<label class="d-flex align-items-center fs-6 fw-semibold mb-2">
																	<span class="">Device</span>
																</label>
																<!--end::Label-->
																<select class="form-control" name="device" required>
																	<option value="desktop" {{ $image->device == 'desktop' ? 'selected' : '' }}>Desktop</option>
																	<option value="mobile" {{ $image->device == 'mobile' ? 'selected' : '' }}> Mobile</option>
																</select>
															</div>

	                            						</div>
	                            						<div class="col-md-2">
	                            							<div class="flex-column mb-3 fv-row">
																<!--begin::Label-->
																<label class="d-flex align-items-center fs-6 fw-semibold mb-2">
																	<span class="">Sort Order </span>
																</label>
																<!--end::Label-->
																<input type="text" class="form-control" name="sort_order" value="{{ $image->sort_order }}" style="padding: 0 15px" />
															</div>
	                            						</div>
	                            						<div class="col-md-2">
	                            							<button type="submit" class=" btn-sm cmn_btn btn_bg_profile" style="margin-top: 30px; padding: 4 8px">Update</button>
	                            						</div>
	                            					</div>
	                            				</form>

	                            				<a href="{{route('admin.page.image.delete', $image->id) }}" class=" btn-sm btn-danger" style="margin-top: 20px; padding: 4 8px" onclick="if(confirm('Do you really want to delete this image?')) { window.location = this.href; } return false;">Delete</a>
                            					
                            				</div>
                            			</div>


                            		@endforeach
                            	@endif

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection