<main class="content">
    <?php
        renderTitle('Cadastro de Usuários', 'Crie e atualize usuário', 'icofont-user');

        include TEMPLATE_PATH . "/messages.php";
    ?>

    <form action="#" method="post">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Nome</label>
                <input type="text" name="name" id="name" class="form-control <?= isset($exception) ? 'is-invalid' : '' ?>" placeholder="Nome" value="<?= isset($name) ? $name : '' ?>">
                <div class="invalid-feedback">
                    <?php if(isset($errors['name'])) echo $errors['name'] ?>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" class="form-control <?= isset($exception) ? 'is-invalid' : '' ?>" placeholder="Informe o e-mail" value="<?= isset($email) ? $email : '' ?>">
                <div class="invalid-feedback">
                    <?php if(isset($errors['email'])) echo $errors['email'] ?>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" class="form-control <?= isset($exception) ? 'is-invalid' : '' ?>" placeholder="Senha">
                <div class="invalid-feedback">
                    <?php if(isset($errors['password'])) echo $errors['password'] ?>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="confirm_password">Confirmar Senha</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control <?= isset($exception) ? 'is-invalid' : '' ?>" placeholder="Confirmar senha">
                <div class="invalid-feedback">
                    <?php if(isset($errors['confirm_password'])) echo $errors['confirm_password'] ?>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="start_date">Data de Admição</label>
                <input type="date" name="start_date" id="start_date" class="form-control <?= isset($exception) ? 'is-invalid' : '' ?>" value="<?= isset($start_date) ? $start_date : '' ?>">
                <div class="invalid-feedback">
                    <?php if(isset($errors['start_date'])) echo $errors['start_date'] ?>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="end_date">Data de desligamento</label>
                <input type="date" name="end_date" id="end_date" class="form-control <?= isset($exception) ? 'is-invalid' : '' ?>" value="<?= isset($end_date) ? $end_date : '' ?>">
                <div class="invalid-feedback">
                    <?php if(isset($errors['end_date'])) echo $errors['end_date'] ?>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="is_admin">Administrador</label>
                <input type="checkbox" name="is_admin" id="is_admin" class="form-control <?= isset($exception) ? 'is-invalid' : '' ?>" <?= isset($is_admin) ? "checked" : "" ?>>
                <div class="invalid-feedback">
                    <?php if(isset($errors['is_admin'])) echo $errors['is_admin'] ?>
                </div>
            </div>
        </div>

        <div class="">
            <button class="btn btn-primary btn-lg">Salvar</button>
            <a class="btn btn-secondary btn-lg" href="/users.php">Cancelar</a>
        </div>
    </form>
</main>