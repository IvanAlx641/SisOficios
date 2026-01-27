@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body p-9">
                    <div class="hstack gap-9">
                        <div class="round-56 rounded-circle text-white d-flex align-items-center justify-content-center text-bg-primary">
                            <i class="ti ti-credit-card fs-6"></i>
                        </div>
                        <div class="align-self-center">
                            <h3 class="mb-1 fs-6">$3249</h3>
                            <span class="text-muted">Total Revenue</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body p-9">
                    <div class="hstack gap-9">
                        <div class="round-56 rounded-circle text-white d-flex align-items-center justify-content-center text-bg-secondary">
                            <i class="ti ti-users fs-6"></i>
                        </div>
                        <div class="align-self-center">
                            <h3 class="mb-1 fs-6">$2376</h3>
                            <span class="text-muted">Online Revenue</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body p-9">
                    <div class="hstack gap-9">
                        <div class="round-56 rounded-circle text-white d-flex align-items-center justify-content-center text-bg-danger">
                            <i class="ti ti-calendar fs-6"></i>
                        </div>
                        <div class="align-self-center">
                            <h3 class="mb-1 fs-6">$1795</h3>
                            <span class="text-muted">Offline Products</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body p-9">
                    <div class="hstack gap-9">
                        <div class="round-56 rounded-circle text-white d-flex align-items-center justify-content-center text-bg-warning">
                            <i class="ti ti-settings fs-6"></i>
                        </div>
                        <div class="align-self-center">
                            <h3 class="mb-1 fs-6">$687</h3>
                            <span class="text-muted">Ad. Expense</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card overflow-hidden">
                <div class="card-body bg-purple">
                    <div class="hstack gap-6 mb-7">
                        <div class="bg-black bg-opacity-10 round-48 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:server-square-linear" class="fs-7 icon-center text-white"></iconify-icon>
                        </div>
                        <div>
                            <h4 class="card-title text-white">Bandwidth usage</h4>
                            <p class="card-subtitle text-white opacity-70">March 2024</p>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h2 class="mb-0 text-white text-nowrap">50 GB</h2>
                        </div>
                        <div class="col-6">
                            <div id="bandwidth-usage"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card overflow-hidden">
                <div class="card-body bg-secondary">
                    <div class="hstack gap-6 mb-7">
                        <div class="bg-white bg-opacity-20 round-48 rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:chart-2-linear" class="fs-7 icon-center text-white"></iconify-icon>
                        </div>
                        <div>
                            <h4 class="card-title text-white">Download count</h4>
                            <p class="card-subtitle text-white opacity-70">March 2024</p>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-5">
                            <h2 class="mb-0 text-white text-nowrap">35487</h2>
                        </div>
                        <div class="col-7">
                            <div id="download-count"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body pb-2">
                    <h4 class="card-title">Our Visitors</h4>
                    <p class="card-subtitle">Different Devices Used to Visit</p>
                    <div id="our-visitors" class="mt-6"></div>
                </div>
                <div class="card-body pt-4 d-flex align-items-center justify-content-center border-top">
                    <ul class="list-inline mb-0 hstack justify-content-center">
                        <li class="list-inline-item px-2 me-0">
                            <div class="text-primary d-flex align-items-center gap-2 fs-3">
                                <iconify-icon icon="ri:circle-fill" class="fs-2"></iconify-icon>Mobile
                            </div>
                        </li>
                        <li class="list-inline-item px-2 me-0">
                            <div class="text-secondary d-flex align-items-center gap-2 fs-3">
                                <iconify-icon icon="ri:circle-fill" class="fs-2"></iconify-icon>Tablet
                            </div>
                        </li>
                        <li class="list-inline-item px-2 me-0">
                            <div class="text-purple d-flex align-items-center gap-2 fs-3">
                                <iconify-icon icon="ri:circle-fill" class="fs-2"></iconify-icon>Desktop
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card blog-widget w-100">
                <div class="card-body p-2">
                    <div class="blog-image">
                        <img src="{{ asset('materialpro/assets/images/backgrounds/blog-bg.jpg') }}" height="273" alt="img" class="w-100 rounded" />
                    </div>
                    <div class="p-9">
                        <div class="badge badge-pill bg-primary-subtle text-primary mb-6">Technology</div>
                        <h4 class="card-title">Business development new rules for 2023</h4>
                        <p class="mb-6 truncate-2 text-muted">
                            Lorem ipsum dolor sit amet, this is a consectetur adipisicing elit.
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn btn-primary">Read more</button>
                            <div class="ms-auto">
                                <a href="javascript:void(0)" class="link" data-bs-toggle="tooltip" title="Share">
                                    <iconify-icon icon="solar:share-linear" class="fs-7"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body pb-3">
                    <div class="d-md-flex no-block">
                        <h4 class="card-title">Projects of the Month</h4>
                        <div class="ms-auto">
                            <select class="form-select">
                                <option selected>January</option>
                                <option value="1">February</option>
                                <option value="2">March</option>
                            </select>
                        </div>
                    </div>
                    <div class="month-table">
                        <div class="table-responsive mt-3">
                            <table class="table align-middle mb-0 no-wrap">
                                <thead>
                                    <tr>
                                        <th class="border-0 ps-0">Client</th>
                                        <th class="border-0">Name</th>
                                        <th class="border-0">Priority</th>
                                        <th class="border-0 text-end">Budget</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="ps-0">
                                            <div class="hstack gap-3">
                                                <span class="round-48 rounded-circle overflow-hidden flex-shrink-0 hstack justify-content-center">
                                                    <img src="{{ asset('materialpro/assets/images/profile/user-2.jpg') }}" alt class="img-fluid">
                                                </span>
                                                <div>
                                                    <h5 class="mb-1">Sunil Joshi</h5>
                                                    <p class="mb-0 fs-3">Web Designer</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><p class="mb-0">Digital Agency</p></td>
                                        <td><span class="badge bg-primary-subtle text-primary">Low</span></td>
                                        <td class="text-end"><p class="mb-0 fs-3">$3.9K</p></td>
                                    </tr>
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body p-2">
                    <img class="card-img-top w-100 profile-bg-height rounded overflow-hidden" src="{{ asset('materialpro/assets/images/backgrounds/profile-bg.jpg') }}" height="111" alt="Card image cap" />
                    <div class="card-body little-profile text-center p-9">
                        <div class="pro-img mb-3">
                            <img src="{{ asset('materialpro/assets/images/profile/user-2.jpg') }}" alt="user" class="rounded-circle shadow-sm" width="112" />
                        </div>
                        <h3 class="mb-1">Angelo Dominic</h3>
                        <p class="fs-3 mb-4">Web Designer &amp; Developer</p>
                        <a href="javascript:void(0)" class="btn btn-primary btn-md btn-rounded mb-7">Follow</a>
                        <div class="row gx-lg-4 text-center pt-4 justify-content-center border-top">
                            <div class="col-4">
                                <h3 class="mb-0">1099</h3>
                                <small class="text-muted fs-3">Articles</small>
                            </div>
                            <div class="col-4">
                                <h3 class="mb-0">23k</h3>
                                <small class="text-muted fs-3">Followers</small>
                            </div>
                            <div class="col-4">
                                <h3 class="mb-0">6035</h3>
                                <small class="text-muted fs-3">Following</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('materialpro/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('materialpro/assets/js/dashboards/dashboard2.js') }}"></script>
@endpush