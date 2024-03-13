<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<a href="{{ route('images', ['sort' => 'filename', 'direction' => $nextDirection]) }}">Сортировать по названию</a>
<a href="{{ route('images', ['sort' => 'upload_time', 'direction' => $nextDirection]) }}">Сортировать по дате загрузки</a>
@foreach($images as $image)
    <div>
        <p>Название файла: {{ $image->filename }}</p>
        <p>Дата и время загрузки: {{ $image->upload_time }}</p>
        <img src="{{ asset('uploads/'.$image->filename) }}" alt="Превью изображения">
        <a href="{{ asset('uploads/'.$image->filename) }}">Просмотреть оригинал</a>.
        <a href="{{ route('download.image', $image->id) }}">Скачать ZIP</a>
    </div>
@endforeach
</body>
</html>
