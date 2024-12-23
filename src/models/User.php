<?php

class User extends Model
{
    protected static $tableName = 'users';
    protected static $columns = ['id','name','password','email','start_date','end_date','is_admin'];

    public static function getActiveUsersCount()
    {
        return static::getCount(['raw' => 'end_date IS NULL']);
    }

    private function validate() {
        $errors = [];

        if (!$this->name) {
            $errors['name'] = 'Nome é um campo obrigatório.';
        }

        if (!$this->email) {
            $errors['email'] = 'E-mail é um campo obrigatório';
        }elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $errors['email'] = 'E-mail invalido';
        }

        if (!$this->password) {
            $errors['password'] = 'Por favor, informe a senha.';
        }

        if (!$this->confirm_password) {
            $errors['password'] = 'Por favor, confirme a senha.';
        }

        if (!$this->start_date) {
            $errors['start_date'] = 'Data de admição é um campo obrigatório.';
        }elseif(!DateTime::createFromFormat('Y-m-d', $this->start_date)) {
            $errors['start_date'] = 'Data de admição deve ter o formato dd/mm/YYYY';
        }

        if($this->end_date && !DateTime::createFromFormat('Y-m-d', $this->end_date)) {
            $errors['start_date'] = 'Data de desligamento deve ter o formato dd/mm/YYYY';
        }

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }

    public function insert()
    {
        $this->validate();
        $this->is_admin = $this->is_admin ? 1 : 0;
        if(!$this->end_date) $this->end_date = null;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::insert();
    }
}