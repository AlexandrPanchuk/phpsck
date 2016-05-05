


<div style="padding: 30px; padding-left: 170px;">
    <?php if(!isset( $_SESSION['regok'])) { ?>
    <p style="font-size: 25px">Регистрация<p> <br>

    <form action="" method="post">
        <table>
            <tr>
                <td width="75"> Логин* </td>
                <td><input type="text" name="login" value="<?php echo @htmlspecialchars($_POST['login']); ?>"> </td>
                <td>  <?php echo @$errors['login']; ?> </td>
            </tr>
            <tr>
                <td> Пароль* </td>
                <td><input type="password" name="password"  value="<?php echo @htmlspecialchars($_POST['password']); ?>"> </td>
                <td><?php echo @$errors['password']; ?></td>
            </tr>
            <tr>
                <td> E-mail* </td>
                <td><input type="text" name="emeil"  value="<?php echo @htmlspecialchars($_POST['emeil']); ?>"> </td>
                <td><?php echo @$errors['emeil']; ?></td>
            </tr>
            <tr>
                <td> Возраст </td>
                <td><input type="text" name="age"  value="<?php echo @htmlspecialchars($_POST['age']); ?>"> </td>
                <td></td>
            </tr>
        </table>
          <br>  <p style="font-size: 10px"> * - обязательно для заполнения </p>
        <input type="submit" name="sendreg" value="Зарегестрироваться">
    </form>
    <?php } else { unset($_SESSION['regok']);//убили сессию  ?>
        <div>Регистрация прошла успешно </div>

    <?php } ?>
</div>