<?php
namespace models;

use base\Glob;
use db\IDatabase;
use db\ActiveRecord;
use validators\RegExValidator;
use validators\LengthValidator;
use validators\RequiredValidator;
use validators\CompareValidator;
use validators\BlacklistValidator;
use validators\UniqueValidator;
use helpers\Words;
use helpers\Json;

/**
 * User
 *
 * @author Volkov Grigorii
 */
class User extends ActiveRecord
{
    public function __construct()
    {
        parent::__construct(['id', 'name', 'email', 'created', 'deleted', 'notes']);
    }

    public function getValidators(): \Generator
    {
        yield new RequiredValidator($this, 'name', 'не может быть пустым');
        yield new RegExValidator($this, 'name', RegExValidator::PATTERN_LETTER_OR_NUMBER, 'может состоять только из символов a-z и 0-9');
        yield new LengthValidator($this, 'name', 8, 'не может быть короче 8 символов', 64, 'не может быть длиннее 64 символов');
        yield new BlacklistValidator($this, 'name', Words::forbiddenWords(), 'не должно содержать слов из списка запрещенных слов');
        yield new UniqueValidator($this, 'name', 'должно быть уникальным');

        yield new RequiredValidator($this, 'email', 'не может быть пустым');
        yield new RegExValidator($this, 'email', RegExValidator::PATTERN_EMAIL, 'должно иметь корректный для e-mail адреса формат');
        yield new LengthValidator($this, 'email', max: 256, maxErrorMessage: 'не может быть длиннее 256 символов');
        yield new BlacklistValidator($this, 'email', Words::forbiddenDomains(), 'не должно принадлежать домену из списка "ненадежных" доменов');
        yield new UniqueValidator($this, 'email', 'должно быть уникальным');

        yield new RequiredValidator($this, 'created', 'не может быть пустым');
        yield new RegExValidator($this, 'created', RegExValidator::PATTERN_DATETIME, 'должно иметь корректный формат датавремя');

        yield new CompareValidator($this, 'deleted', '>=', 'created', 'не может быть меньше значения поля created');
        yield new RegExValidator($this, 'deleted', RegExValidator::PATTERN_DATETIME, 'должно иметь корректный формат датавремя');
    }

    public static function getDb(): IDatabase
    {
        return Glob::$app->getDb();
    }

    public static function getTableName(): string
    {
        return 'users';
    }

    public static function primaryKey(): string
    {
        return 'id';
    }

    public function softDelete(): bool
    {
        if ($this->getAttribute('deleted')) {
            $this->addError('deleted', 'Пользователь удален');
            return false;
        }
        $this->setAttribute('deleted', date('Y-m-d H:i:s'));
        return $this->save();
    }

    protected function afterSave(bool $insert): void
    {
        if (!$insert) {
            Glob::info('Обновление User #' . $this->getPrimaryKey() . ' (' . Json::encode($this->getAttributes(['name', 'email', 'created', 'deleted', 'notes'])) . ')');
        }
    }
}
