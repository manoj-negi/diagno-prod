<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Payment</title>
</head>
<body>
    <h1>Razorpay Payment</h1>

    <button id="rzp-button1">Pay ₹{{ number_format($amount / 100, 2) }}</button>

    <form action="{{ route('payment.callback') }}" method="POST" id="payment-form">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="{{ $order_id }}">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    </form>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var options = {
            "key": "{{ env('RAZORPAY_KEY') }}", // Razorpay Key ID
            "amount": "{{ $amount }}", // Amount in paise
            "currency": "INR",
            "name": "Your Website Name",
            "description": "Payment for Order #{{ $order_id }}",
            "order_id": "{{ $order_id }}", // Pass the order ID
            "handler": function (response){
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                document.getElementById('payment-form').submit();
            },
            "prefill": {
                "name": "{{ auth()->user()->name ?? 'Your Name' }}",
                "email": "{{ auth()->user()->email ?? 'email@example.com' }}",
                "contact": "{{ auth()->user()->phone ?? '1234567890' }}"
            }
        };

        var rzp1 = new Razorpay(options);
        document.getElementById('rzp-button1').onclick = function(e){
            rzp1.open();
            e.preventDefault();
        }
    </script>
</body>
</html>
