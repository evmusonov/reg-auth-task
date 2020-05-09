<?php
?>

<?php if (isset($_SESSION['notification'])) { ?>
    <p role="alert" class="alert alert-primary mt-4"><?= $_SESSION['notification'] ?></p>
    <?php unset($_SESSION['notification']); ?>
<?php } ?>

<div class="row justify-content-center mt-5">
    <div class="col-sm-6">
        <div>
            <h1 class="mb-5">
                Личный кабинет
                <span class="float-right mt-3 h6"><a href="/logout">Выйти</a></span>
            </h1>
            <?php if (isset($dataErrors)) { ?>
                <div role="alert" class="alert alert-danger">
                    <ul>
                        <?php foreach ($dataErrors as $error) { ?>
                            <li><?= $error ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <form action="/change-data" method="post">
                <div class="form-group">
                    <input type="text" name="fio" class="form-control" placeholder="ФИО" value="<?= htmlspecialchars($_SESSION['user']['fio']) ?>">
                </div>
                <button type="submit" class="btn btn-primary">Изменить</button>
            </form>
        </div>
    </div>
</div>
<div class="row justify-content-center mt-5">
    <div class="col-sm-6">
        <div>
            <h3 class="mb-5">Изменить пароль</h3>
            <?php if (isset($passwordErrors)) { ?>
                <div role="alert" class="alert alert-danger">
                    <ul>
                        <?php foreach ($passwordErrors as $error) { ?>
                            <li><?= $error ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <form action="/change-password" method="post">
                <div class="form-group">
                    <input type="password" name="old_password" class="form-control" placeholder="Текущий пароль">
                </div>
                <div class="form-group">
                    <input type="password" name="new_password" class="form-control" placeholder="Новый пароль">
                </div>
                <div class="form-group">
                    <input type="password" name="repeat_password" class="form-control" placeholder="Повторите пароль">
                </div>
                <button type="submit" class="btn btn-primary">Изменить</button>
            </form>
        </div>
    </div>
</div>
