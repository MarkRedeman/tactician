<?php

namespace Tactician\CommandBus\Handler\Locator;

use Tactician\CommandBus\Command;
use Tactician\CommandBus\Exception\MissingHandlerException;

/**
 * Fetch handler instances from an in-memory collection.
 *
 * This locator allows you to bind a handler object to receive commands of a
 * certain class name. For example:
 *
 *      // Wire everything together
 *      $myHandler = new TaskAddedHandler($dependency1, $dependency2);
 *      $inMemoryLocator->addHandler($myHandler, 'My\TaskAddedCommand');
 *
 *      // Returns $myHandler
 *      $inMemoryLocator->getHandlerForCommand(new My\TaskAddedCommand());
 */
class InMemoryLocator implements HandlerLocator
{
    /**
     * @var object[]
     */
    protected $handlers = [];

    /**
     * Bind a handler instance to receive all commands with a certain class
     *
     * @param object $handler Handler to receive class
     * @param string $commandClassName Command class e.g. "My\TaskAddedCommand"
     */
    public function addHandler($handler, $commandClassName)
    {
        $this->handlers[$commandClassName] = $handler;
    }

    /**
     * Retrieve handler for the given command
     *
     * @param Command $command
     * @return object
     * @throws MissingHandlerException
     */
    public function getHandlerForCommand(Command $command)
    {
        $className = get_class($command);

        if (!isset($this->handlers[$className])) {
            throw MissingHandlerException::forCommand($command);
        }

        return $this->handlers[$className];
    }
}
