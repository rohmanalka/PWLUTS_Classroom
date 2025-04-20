<section class="content-header pt-2 pb-4">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-md-6">
                <h3 class="fw-bold mb-0">{{ $breadcrumb->title ?? 'Page Title' }}</h3>
                <h6 class="op-7">{{ $breadcrumb->subtitle ?? '' }}</h6>
            </div>
            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                <ol class="breadcrumb float-sm-end mb-0 justify-content-md-end justify-content-start">
                    @if(isset($breadcrumb->list))
                        @foreach ($breadcrumb->list as $key => $value)
                            @if ($key == count($breadcrumb->list) -1)   
                                <li class="breadcrumb-item active">{{ $value }}</li>
                            @else 
                                <li class="breadcrumb-item">{{ $value }}</li>
                            @endif
                        @endforeach
                    @endif
                </ol>
            </div>
        </div>
    </div>
</section>
