@include('src/header',['title' => 'Тесты'])
@include('src/admintitle', ['title' => 'Тесты'])
<ul>загрузка</ul>
<script src='/js/ApiRequest.js'></script>
<script>
    var ul = document.querySelector('ul');
    var api = new ApiRequest('/admin/api/kims');
    api.getJSON(response => {
        var html = '';
        if(!response.ok) return;
        kims = response.data;
        for(var i in kims) {
            html += '<li><a href="/admin/test/'+kims[i]+'">'+kims[i]+'</a></li>';
        }
        ul.innerHTML = html;
    });    
</script>
@include('src/footer')