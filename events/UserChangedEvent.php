<?php
namespace events;

use helpers\Json;
use models\User;
use loggers\ILogger;
use loggers\LogLevel;

/**
 * UserChangedEvent
 *
 * @author Volkov Grigorii
 */
class UserChangedEvent
{
    public function __construct(
        public User $model,
        public ILogger $logger,
    ) {}

    public function dispatch()
    {
        $this->logger->log(LogLevel::Info, 'Обновление Пользователя #' . $this->model->getAttribute($this->model->primaryKey()) . ' (' . Json::encode($this->model->getAttributes()) . ')');
    }
}
