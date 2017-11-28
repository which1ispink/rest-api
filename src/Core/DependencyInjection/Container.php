<?php
namespace Which1ispink\API\Core\DependencyInjection;

/**
 * Class Container
 *
 * A simplified dependency injection container
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
class Container implements ContainerInterface
{
    /**
     * @var array
     */
    private $services = [];

    /**
     * @inheritdoc
     */
    public function set(string $id, \Closure $service): self
    {
        if (! $this->has($id)) {
            $this->services[$id] = $service;
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function get(string $id)
    {
        if (! $this->has($id)) {
            throw new \InvalidArgumentException(
                sprintf('Identifier "%s" is not defined.', $id)
            );
        }

        return $this->services[$id]($this);
    }

    /**
     * @inheritdoc
     */
    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }
}
