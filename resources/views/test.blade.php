@include('src/header',['title' => 'Тест'])
<table>
    <tr>
        <td>
            <table class='pageButtons'>
            </table>
        </td>
        <td>
        <img>
        </td>
        <td>
            <table>
                <tr>
                    <td>
                        <textarea></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <button onclick="clearAnswer()">Очистить</button>
                                </td>
                                <td>
                                    <button onclick="saveAnswer()">Сохранить</button>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <ul>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button>Завершить экзамен</button>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<script src='/js/ApiRequest1.js'></script>
<script src='/js/test.js'></script>
@include('src/footer')