

<form name="comment" action="" method="post" style="padding-left: 20px; padding-top: 20px;">
    <p style="font-size: 30px; "> Комментарии к записи: </p> <br>
    <p>
        <label>Имя:</label>
        <input type="text" name="name_id" value="<?php echo @htmlspecialchars($_POST['name_id']); ?>" />
        <p style="color: red;"> <?php echo @$err1; ?> </p>
    </p>
    <p>
        <label>Комментарий:</label>
        <br />
        <textarea name="text_comment" cols="60" rows="5"  value="<?php echo @htmlspecialchars($_POST['text_comment']); ?>"> </textarea>
         <p style="color: red;"><?php echo @$err; ?> </p>
    </p>
    <p>
        <input type="hidden" name="page_id" value="" />
        <input type="submit" value="Отправить" />
    </p>
</form>



