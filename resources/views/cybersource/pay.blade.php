<html>
    <head>
        <title>Cyber Source SelfHosted Payment</title>        
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    </head>    
    <body class="font-sans antialiased text-gray-600 min-h-full flex flex-col [overflow-anchor:none]">
        <div class="relative mx-auto mt-20 w-full max-w-container px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Cybersource Confirm Payment Testing</h2>
                <div class="mt-10">
                    <form id="frm-nicasia" action="https://testsecureacceptance.cybersource.com/pay" method="post">
                        @csrf
                        <div class="mt-6 flex items-center gap-x-6">
                            <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                            <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
                        </div>
                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <fieldset>
                            @foreach($form_data as $key=>$value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}"/>
                                <div class="sm:col-span-2">
                                    <div class="text-sm leading-6">
                                        <label for="comments" class="font-medium text-gray-900">{{$key}}</label>
                                        <p class="text-gray-500">{{ $value }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </fieldset>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </body>

</html>
