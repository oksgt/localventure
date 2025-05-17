@extends('admin.layout.master')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">Selamat datang, {{ Auth::user()->name }}</h3>
                        <h5 class="text-muted">( {{ Auth::user()->role->name }} )</h5>
                    </div>
                    <div class="col-12 col-xl-4">
                        <div class="justify-content-end d-flex">
                            <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                <button class="btn btn-sm btn-light bg-white" type="button" id="dropdownMenuDate2"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="ti-calendar"></i> Today
                                    ({{ \Carbon\Carbon::now()->locale('en')->isoFormat('DD MMM YYYY') }})
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            @foreach ($destinations as $item)
                <div class="col-md-6 grid-margin stretch-card">
                    <a href="{{ route('form-ticket-purchase.index', ['destinationId' => $item->id]) }}" class="card tale-bg" style="text-decoration: none; color: inherit;">
                        <div class="card-people mt-auto pt-0">
                            <img src="{{ asset('storage/destination/' . basename($item->images->first()->image_url)) }}" alt="">
                            <div class="weather-info">
                                <div class="d-flex flex-row text-white align-items-center">
                                    <div>
                                        <h2 class="mb-0 font-weight-bold"><i class="icon-map mr-2"></i></h2>
                                    </div>
                                    <div class="ml-2">
                                        <h4 class="location font-weight-bold">{{ $item->name }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endsection

    @push('scripts')
        <script src="{{ asset('admin-page') }}/vendors/select2/select2.min.js"></script>
    @endpush
