<!DOCTYPE html>

<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link href="public/css/main.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    {block name=head}{/block}
</head>

<body>
{block name=header}{/block}
<div class="container">
    {block name=error}{/block}

    {block name=body}{/block}

    {block name=footer}{/block}
</div>
</body>

</html>