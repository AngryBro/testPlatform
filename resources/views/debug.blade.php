@include('src/header',['title' => 'debug'])
<form action="/debug">
    <input type="text" name='param1' />
    <button type="submit">get</button>
</form>
@include('src/footer')