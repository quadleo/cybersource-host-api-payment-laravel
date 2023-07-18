<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CyberSource Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans antialiased text-gray-600 min-h-full flex flex-col [overflow-anchor:none]">
    <div class="relative mx-auto mt-20 w-full max-w-container px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center">
            <h2 class="text-base font-semibold leading-7 text-gray-900">Cybersource Payment Testing</h2>
            <p class="mt-1 text-sm leading-6 text-gray-600">This information will be displayed publicly so be careful
                what you share.</p>
            <div class="mt-10">
                <form id="frm-nicasia" action="{{ route('confirm-pay') }}" method="post">
                    @csrf
                    <input type="hidden" name="access_key" value="{{ @$data['access_key'] }}">
                    <input type="hidden" name="profile_id" value="{{ @$data['profile_id'] }}">
                    <input type="hidden" name="transaction_uuid" value="{{ @$data['transaction_uuid'] }}">
                    <input type="hidden" name="signed_field_names"
                        value="access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency,payment_method,bill_to_forename,bill_to_surname,bill_to_email,bill_to_phone,bill_to_address_line1,bill_to_address_city,bill_to_address_state,bill_to_address_country,bill_to_address_postal_code">
                    <input type="hidden" name="unsigned_field_names" value="card_type,card_number,card_expiry_date">
                    <input type="hidden" name="signed_date_time" value="{{ @$data['signed_date_time'] }}">
                    <input type="hidden" name="locale" value="en">
                    <input type="hidden" name="auth_trans_ref_no" value="">
                    {{--  <input type="hidden" name="bill_to_forename" value="Basant">
                     <input type="hidden" name="bill_to_surname" value="Joshi">
                     <input type="hidden" name="bill_to_email" value="joshibasantraj@gmail.com">
                     <input type="hidden" name="bill_to_phone" value="9742395923">
                     <input type="hidden" name="bill_to_address_line1" value="Kathmandu">
                     <input type="hidden" name="bill_to_address_city" value="Kathmandu">
                     <input type="hidden" name="bill_to_address_state" value="Kathmandu">
                     <input type="hidden" name="bill_to_address_country" value="NP">
                     <input type="hidden" name="bill_to_address_postal_code" value="Kathmandu">  --}}
                    <input type="hidden" name="bill_to_forename" value="Ashish">
                    <input type="hidden" name="bill_to_surname" value="dahal">
                    <input type="hidden" name="bill_to_email" value="ashishdahal490@gmail.com">
                    <input type="hidden" name="bill_to_phone" value="9801111111">
                    <input type="hidden" name="bill_to_address_line1" value="baneshwor">
                    <input type="hidden" name="bill_to_address_city" value="kathmandu">
                    <input type="hidden" name="bill_to_address_state" value="kathmandu">
                    <input type="hidden" name="bill_to_address_country" value="NP">
                    <input type="hidden" name="bill_to_address_postal_code">

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <div class="mt-2">
                                <input type="text" name="amount" id="amount" autocomplete="amount" value="1000"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <div class="mt-2">
                                <input type="text" name="transaction_type" id="transaction_type"
                                    autocomplete="transaction_type" value="sale"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <div class="mt-2">
                                <input type="text" name="reference_number" id="reference_number"
                                    autocomplete="reference_number" value="1234"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <div class="mt-2">
                                <input type="text" name="currency" id="currency" autocomplete="currency"
                                    value="NP"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="card" name="payment_method" placeholder="">
                    <input type="hidden" name="signature" value="">
                    <input type="hidden" name="card_type" value="001">
                    <input type="hidden" name="card_number" value="">
                    <input type="hidden" name="card_expiry_date" value="">
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <a href="{{ route('home') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                        <button type="submit"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
