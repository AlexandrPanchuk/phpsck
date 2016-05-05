<form action="" method="post" style="padding-left: 20px; padding-top: 20px;">

    <div>
        Заголовок новости: <br>
        <input type="text" name="title">  <!--  СДЕЛАТЬ ПРОВЕРКУ НА ОШИБКИ !!!!!!!!!!!!!!!!!!!!!!     -->
    </div>

    <div>
        Категория новости: <br>
        <input type="text" name="cat">
    </div>

    <div>
        Описание новости: <br>
        <textarea name="description" cols="60" rows="5"> </textarea>
    </div>

    <div>
        Полный текст новости: <br>
        <textarea name="text" cols="60" rows="5"> </textarea>
    </div>

    <input type="submit" name="add" value="Добавить новость">

</form>