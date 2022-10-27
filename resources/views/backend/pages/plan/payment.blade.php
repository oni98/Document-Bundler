@extends('backend.layouts.choosePlan')

@section('template_title')
    Choose Payment Gateway
@endsection

@push('custom-css')
    <style>
        .white-mode {
            text-decoration: none;
            padding: 17px 40px;
            background-color: yellow;
            border-radius: 3px;
            color: black;
            transition: .35s ease-in-out;
            position: absolute;
            left: 15px;
            bottom: 15px
        }

        .pricingTable {
            text-align: center;
            background: #fff;
            margin: 0 -15px;
            box-shadow: 0 0 10px #ababab;
            padding-bottom: 40px;
            border-radius: 10px;
            color: #cad0de;
            transform: scale(1);
            transition: all .5s ease 0s
        }

        .pricingTable:hover {
            transform: scale(1.05);
            z-index: 1
        }

        .pricingTable .pricingTable-header {
            padding: 40px 0;
            background: #f5f6f9;
            border-radius: 10px 10px 50% 50%;
            transition: all .5s ease 0s
        }

        .pricingTable:hover .pricingTable-header {
            background: #ff9624
        }

        .pricingTable .pricingTable-header i {
            font-size: 50px;
            color: #858c9a;
            margin-bottom: 10px;
            transition: all .5s ease 0s
        }

        .pricingTable .price-value {
            font-size: 35px;
            color: #ff9624;
            transition: all .5s ease 0s
        }

        .pricingTable .month {
            display: block;
            font-size: 14px;
            color: #cad0de
        }

        .pricingTable:hover .month,
        .pricingTable:hover .price-value,
        .pricingTable:hover .pricingTable-header i {
            color: #fff
        }

        .pricingTable .heading {
            font-size: 24px;
            color: #ff9624;
            margin-bottom: 20px;
            text-transform: uppercase
        }

        .pricingTable .pricing-content ul {
            list-style: none;
            padding: 0;
            margin-bottom: 30px
        }

        .pricingTable .pricing-content ul li {
            line-height: 30px;
            color: #a7a8aa
        }

        .pricingTable .pricingTable-signup button {
            display: inline-block;
            font-size: 15px;
            color: #fff;
            padding: 10px 35px;
            border-radius: 20px;
            background: #ffa442;
            text-transform: uppercase;
            transition: all .3s ease 0s
        }

        .pricingTable .pricingTable-signup button:hover {
            box-shadow: 0 0 10px #ffa442
        }

        .pricingTable.blue .heading,
        .pricingTable.blue .price-value {
            color: #4b64ff
        }

        .pricingTable.blue .pricingTable-signup button,
        .pricingTable.blue:hover .pricingTable-header {
            background: #4b64ff
        }

        .pricingTable.blue .pricingTable-signup button:hover {
            box-shadow: 0 0 10px #4b64ff
        }

        .pricingTable.red .heading,
        .pricingTable.red .price-value {
            color: #ff4b4b
        }

        .pricingTable.red .pricingTable-signup button,
        .pricingTable.red:hover .pricingTable-header {
            background: #ff4b4b
        }

        .pricingTable.red .pricingTable-signup button:hover {
            box-shadow: 0 0 10px #ff4b4b
        }

        .pricingTable.green .heading,
        .pricingTable.green .price-value {
            color: #40c952
        }

        .pricingTable.green .pricingTable-signup button,
        .pricingTable.green:hover .pricingTable-header {
            background: #40c952
        }

        .pricingTable.green .pricingTable-signup button:hover {
            box-shadow: 0 0 10px #40c952
        }

        .pricingTable.blue:hover .price-value,
        .pricingTable.green:hover .price-value,
        .pricingTable.red:hover .price-value {
            color: #fff
        }

        @media screen and (max-width:990px) {
            .pricingTable {
                margin: 0 0 20px
            }
        }
    </style>
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">
                    <div class="demo">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-6">
                                    <form action="{{ route('payment.paypal') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="amount" value="{{ $price }}">
                                        <input type="hidden" name="package_id" value="{{ $package_id }}">
                                        <input type="hidden" name="gateway" value="paypal">
                                        <button type="submit" class="btn btn-primary btn-block">PAY BY
                                            PAYPAL</button>
                                    </form>
                                </div>
                                <div class="col-sm-6">
                                    <form action="{{ route('payment.strippe.index') }}" method="get">
                                        <input type="hidden" name="amount" value="{{ $price }}">
                                        <input type="hidden" name="package_id" value="{{ $package_id }}">
                                        <input type="hidden" name="gateway" value="strippe">
                                        <button type="submit" class="btn btn-primary btn-block">PAY BY
                                            STRIPE</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- /.Left col -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <!-- /.content-wrapper -->
@endsection

@push('custom-script')
@endpush
