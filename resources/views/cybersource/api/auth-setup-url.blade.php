<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberSource Auth</title>
</head>

<body>
    <h2>Step-Up IFrame</h2>
    <hr />

    <div style="width: 100%; text-align: center">
        <iframe name="step-up-iframe" width="500" height="600" frameBorder="1"></iframe>
        <form id="step-up-form" target="step-up-iframe" method="post" action="{{ $data['cardinalStepUpURL'] }}">
            <input type="hidden" name="JWT" value="{{ $data['jwt'] }}" />
            <input type="hidden" name="MD" value="optionally_include_custom_data_that_will_be_returned_as_is" />
        </form>
    </div>
</body>
<script>
    window.onload = function() {
        var stepUpForm = document.querySelector('#step-up-form');
        if (stepUpForm) stepUpForm.submit();
    }
</script>

</html>
