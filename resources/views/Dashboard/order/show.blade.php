@extends('Dashboard.layouts.master')
@section('title', 'بيانات طلب')
@section('style')
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">
                        <!-- <div class="panel-heading">
                            <h4>Invoice</h4>
                        </div> -->
                        <div class="panel-body">
                            <div class="clearfix">
                                <div class="float-left">
                                    <h3>{{config('app.name')}}</h3>
                                </div>
                                <div class="float-right">
                                    <h4>فاتورة # <br>
                                        <strong>{{$order->id}}</strong>
                                    </h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="float-left mt-3">
                                        <address>
                                            <strong>Twitter, Inc.</strong><br>
                                            795 Folsom Ave, Suite 600<br>
                                            San Francisco, CA 94107<br>
                                            <abbr title="Phone">P:</abbr> (123) 456-7890
                                        </address>
                                    </div>
                                    <div class="float-right mt-3">
                                        <p>
                                            <strong> تاريخ الطلب: </strong>
                                            {{\Carbon\Carbon::parse($order->created_at)->format('Y-M-d H:i')}}
                                        </p>
                                        <p>
                                            <strong> تاريخ الاستلام: </strong>
                                            {{\Carbon\Carbon::parse($order->deliver_at)->format('Y-M-d H:i')}}
                                        </p>
                                        <p class="m-t-10"><strong>Order Status: </strong> <span class="label label-pink">Pending</span></p>
                                        <p class="m-t-10"><strong>Order ID: </strong> #123456</p>
                                    </div>
                                </div><!-- end col -->
                            </div>
                            <!-- end row -->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table mt-4">
                                            <thead>
                                            <tr><th>#</th>
                                                <th>Item</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Unit Cost</th>
                                                <th>Total</th>
                                            </tr></thead>
                                            <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>LCD</td>
                                                <td>Lorem ipsum dolor sit amet.</td>
                                                <td>1</td>
                                                <td>$380</td>
                                                <td>$380</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Mobile</td>
                                                <td>Lorem ipsum dolor sit amet.</td>
                                                <td>5</td>
                                                <td>$50</td>
                                                <td>$250</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>LED</td>
                                                <td>Lorem ipsum dolor sit amet.</td>
                                                <td>2</td>
                                                <td>$500</td>
                                                <td>$1000</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>LCD</td>
                                                <td>Lorem ipsum dolor sit amet.</td>
                                                <td>3</td>
                                                <td>$300</td>
                                                <td>$900</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Mobile</td>
                                                <td>Lorem ipsum dolor sit amet.</td>
                                                <td>5</td>
                                                <td>$80</td>
                                                <td>$400</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6 col-6">
                                    <div class="clearfix mt-4">
                                        <h5 class="small text-dark">PAYMENT TERMS AND POLICIES</h5>

                                        <small>
                                            All accounts are to be paid within 7 days from receipt of
                                            invoice. To be paid by cheque or credit card or direct payment
                                            online. If account is not paid within 7 days the credits details
                                            supplied as confirmation of work undertaken will be charged the
                                            agreed quoted fee noted above.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-6 offset-xl-3">
                                    <p class="text-right"><b>Sub-total:</b> 2930.00</p>
                                    <p class="text-right">Discout: 12.9%</p>
                                    <p class="text-right">VAT: 12.9%</p>
                                    <hr>
                                    <h3 class="text-right">USD 2930.00</h3>
                                </div>
                            </div>
                            <hr>
                            <div class="d-print-none">
                                <div class="float-right">
                                    <a href="javascript:window.print()" class="btn btn-dark waves-effect waves-light"><i class="fa fa-print"></i></a>
                                    <a href="#" class="btn btn-primary waves-effect waves-light">Submit</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
