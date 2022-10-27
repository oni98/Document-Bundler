<script src="https://js.stripe.com/v3/"></script>
<style>
    .StripeElement {
        box-sizing: border-box;

        height: 40px;

        padding: 10px 12px;

        border: 1px solid transparent;
        border-radius: 4px;
        background-color: white;

        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }

    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
        border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
</style>
@if ($message = Session::get('success'))
    <div class="success">
        <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = Session::get('error'))
    <div class="error">
        <strong>{{ $message }}</strong>
    </div>
@endif

<form action="{{ route('payment.strippe', [$package_id]) }}" method="post" id="payment-form">
    <div class="form-row">
        <p><input type="hidden" value="{{ $amount }}" name="amount" /></p>
        <p><input type="hidden" name="email" value="{{ auth()->user()->email }}" /></p>
        <label for="card-element">
            Credit or debit card
        </label>
        <div id="card-element">
            <!-- A Stripe Element will be inserted here. -->
        </div>

        <!-- Used to display form errors. -->
        <div id="card-errors" role="alert"></div>
    </div>
    <p><button>Submit Payment</button></p>
    {{ csrf_field() }}
</form>
<script>
    var publishable_key = "{{ data_get($STRIPE_PUBLISHABLE_KEY, 'value', '') }}";
</script>
<script src="{{ asset('/js/card.js') }}"></script>
