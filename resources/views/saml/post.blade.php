<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>POST Data</title>
</head>
<body>

<form method="post" action="{{ $url }}">
    @foreach($data as list('name' => $name, 'value' => $value))
        <input type="hidden" name="{{ $name }}" value="{{ $value }}" />
    @endforeach

    <input type="submit" style="display:none;" />
</form>

<script>
    document.getElementsByTagName('input')[0].click();
</script>
</body>
</html>