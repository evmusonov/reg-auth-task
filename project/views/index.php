<?php

?>

<?php if (isset($_SESSION['notification'])) { ?>
    <p role="alert" class="alert alert-primary mt-4"><?= $_SESSION['notification'] ?></p>
    <?php unset($_SESSION['notification']); ?>
<?php } ?>

<div class="row mt-5">
    <div class="col-sm-5">
        <div>
            <h2 class="mb-5">Регистрация</h2>
            <?php if (isset($regErrors)) { ?>
                <div role="alert" class="alert alert-danger">
                    <ul>
                        <?php foreach ($regErrors as $error) { ?>
                            <li><?= $error ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <form action="/register" method="post">
                <div class="form-group">
                    <input type="text" name="fio" class="form-control" placeholder="ФИО" value="<?= $_POST['fio'] ?? htmlspecialchars($_POST['fio']) ?>">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="login" placeholder="Логин" value="<?= $_POST['login'] ?? htmlspecialchars($_POST['login']) ?>">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="E-mail" value="<?= $_POST['email'] ?? htmlspecialchars($_POST['email']) ?>">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Пароль">
                </div>
                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
            </form>
        </div>
    </div>
    <div class="col-sm-2"></div>
    <div class="col-sm-5">
        <div>
            <h2 class="mb-5">Авторизация</h2>
            <?php if (isset($loginErrors)) { ?>
                <div role="alert" class="alert alert-danger">
                    <ul>
                        <?php foreach ($loginErrors as $error) { ?>
                            <li><?= $error ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <form action="/login" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="email" placeholder="E-mail" value="<?= $_POST['email'] ?? htmlspecialchars($_POST['email']) ?>">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Пароль">
                </div>
                <button type="submit" class="btn btn-primary">Войти</button>
            </form>
        </div>
    </div>
</div>
