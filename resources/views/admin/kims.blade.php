@include('src/header',['title' => 'КИМы'])
@include('src/admintitle',['title' => 'Управление КИМами'])
<table>
    <tr>
        <td>КИМ</td>
        <td>Отметка на удаление</td>
    </tr>
    <tr>
        <td>Загрузка</td>
        <td>Loading</td>
    </tr>
    <tr>
        <td><button onclick='addForm()'>Добавить</button></td>
        <td><button onclick='del()'>Удалить</button></td>
    </tr>
</table>
<form enctype="multipart/form-data" hidden>
    <table>
        <tr>
            <td>ID КИМ:</td>
            <td><input type='text' name='kim'/></td>
        </tr>
        <tr>
            <td>Файл:</td>
            <td><input type='file' name='file'/></td>
        </tr>
        <tr>
            <td></td>
            <td><button type='button' onclick="add()">Загрузить</button></td>
        </tr>
    </table>
</form>
<script src='/js/ApiRequest1.js'></script>
<script src='/js/kims.js'></script>
@include('src/footer')