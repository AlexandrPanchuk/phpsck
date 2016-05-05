
<div style="padding-left: 40px; padding-top: 20px; padding-bottom: 20px;">
    <?php if (isset($info)) { ?> <!-- Вывод об успешном добавлении новости "Новость была добавлена" -->
    <p style="font-size: medium; font-size: large; color: darkgreen;"> <?php echo $info; ?> </p>
    <?php }?>

    <a href="/index.php?module=news&page=add" style="color: #1378BF;">Добавить новую новость</a>


    <form action="" method="post">
        <?php while ($row = mysqli_fetch_assoc($news)) { ?>
            <div>
                <hr>
                    <div><input type="checkbox" name="ids[]" value="<?php echo $row['id']; ?>">
                    <a href="index.php?module=news&page=edit&id=<?php echo $row['id']; ?>" style="color: #545397;"> Отредактировать </a>
                    <a href="index.php?module=news&page=news&action=delete&id=<?php echo $row['id']; ?>" style="color: #545397;"> Удалить </a> <b>
                    <?php echo $row['title'] ?></b>  <span style="color: #999999; font-size: 10px;" >
                    <?php  echo $row['date'];  ?> </span>  </div>
                </hr>
            </div>
        <?php } ?>
        <input type="submit" name="delete" value="Удалить отмеченные записи">
    </form>

</div>