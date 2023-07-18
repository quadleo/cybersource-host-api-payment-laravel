<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberSource Auth</title>
</head>
<body>

<h2>Device Data Collection Iframe</h2>
<hr/>

<iframe id="cardinal_collection_iframe" name="collectionIframe" height="10" width="10" style="display: none;"></iframe>

<form id="cardinal_collection_form" method="POST" target="collectionIframe" action="{{ $data['deviceDataCollectionURL'] }}">
    <input id="cardinal_collection_form_input" type="hidden" name="JWT" value="{{ $data['accessToken'] }}">
</form>

event.data: <pre id="event_data"></pre>

</body>
<script>
    window.onload = function() {
        var cardinalCollectionForm = document.querySelector('#cardinal_collection_form');
        if (cardinalCollectionForm) cardinalCollectionForm.submit();
    }

    window.addEventListener('message', function(event) {
        if (event.origin === '<?php echo $data['cardinalCollectionFormOrigin'] ?>') {
            console.log(event.data);
            // alert(event.data);
            let event_data = document.querySelector('#event_data');
            event_data.innerHTML = JSON.stringify(event.data, null, 2);
        }
    }, false);

    document.querySelector('#event_data').innerHTML = "...";

</script>
</html>