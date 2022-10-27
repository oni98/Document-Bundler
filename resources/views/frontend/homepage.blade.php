@extends('frontend.layouts.app')
@section('main_section')
    <div class="main">
        <!-- banner section start  -->
        <section class="mh-500 section-bg d-flex align-items-center" style="background-image: url(frontend/img/banner.jpg);">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6 text-white">
                        <h2 class="fz-50 pb-3">Bundle-B</h2>
                        <p>Simple bundeling solution for busy lawyer's</p>
                        <p>Create secure Court bundles quickly, easy and free</p>
                        <a href="{{route('login')}}" class="btn btn-primary mt-3">Start bundeling</a>
                    </div><!-- col./  -->
                </div><!-- row./  -->
            </div><!-- container./  -->
        </section>

        <!-- banner bottom section start  -->
        <section class="mt-50-2 pb-100">
            <div class="container">
                <div class="row g-0 shadow-1">
                    <div class="col-12 col-lg-3 col-md-6 bdr-right-1">
                        <div class="box bg-white p-4">
                            <h5>Starter</h5>
                            <h2 class="fw-600 fz-24">Easy and quick</h2>
                            <p>User frendly inferface. Just click and drop upload your documents.</p>
                        </div>
                    </div><!-- col./  -->

                    <div class="col-12 col-lg-3 col-md-6 bdr-right-1">
                        <div class="box bg-white p-4">
                            <h5>Starter</h5>
                            <h2 class="fw-600 fz-24">Flexible</h2>
                            <p>Delete, upload, rename, rearange. Do wtahever you need. It is flexible and functional.</p>
                        </div>
                    </div><!-- col./  -->

                    <div class="col-12 col-lg-3 col-md-6 bdr-right-1">
                        <div class="box bg-white p-4">
                            <h5>Starter</h5>
                            <h2 class="fw-600 fz-24">Secure & Compliant</h2>
                            <p>Data security and daily backups. Bundeling tempales for for all levels of bundle production
                            </p>
                        </div>
                    </div><!-- col./  -->

                    <div class="col-12 col-lg-3 col-md-6">
                        <div class="box bg-white p-4">
                            <h5>Starter</h5>
                            <h2 class="fw-600 fz-24">FREE</h2>
                            <p>For the occasional creation of small bundles. </p>
                        </div>
                    </div><!-- col./  -->

                </div><!-- row./  -->
            </div><!-- container./  -->
        </section>

        <!-- intro section start  -->
        <section class="">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2 class="text-shadow-1 fz-50 pb-3 fz-30sm">What is Bundle-B?</h2>
                        <p>
                            It's document bundling at its best! Take documents from anywhere - your folder, case or document
                            management system. Bundledocs organizes them into a neat, numbered, indexed and sectioned
                            booklet in minutes. Instantly ready to save, share or print. No matter how big or small, you can
                            change in seconds. Simple, easy to use, time-saving and massively efficient. No upfront costs,
                            no training needed, no minimum terms - just get going!
                        </p>
                    </div><!-- col./  -->
                </div><!-- row./  -->

                <div class="row">
                    <div class="col-12">
                        <img src="{{asset('frontend/img/process-1.PNG')}}" alt="process" class="w-100 img-fluid">
                    </div><!-- col./  -->
                </div><!-- row./  -->
            </div><!-- container./  -->
        </section>

        <!-- cta section start  -->
        <section class="bg-color-1 py-5">
            <div class="container">
                <div class="row align-items-center text-center text-md-start">
                    <div class="col-12 col-md-8 mb-4 mb-md-0">
                        <h3 class="fz-40 fz-30sm">Get Started with Bundle-B Today...</h3>
                    </div><!-- col./  -->

                    <div class="col-12 col-md-4">
                        <a href="{{route('register')}}" class="btn btn-primary">Try for free</a>
                    </div><!-- col./  -->
                </div><!-- row./  -->
            </div><!-- container./  -->
        </section>
    </div>
@endsection
