<?php
namespace Which1ispink\API\Core\DependencyInjection;

/**
 * Interface ContainerInterface
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
interface ContainerInterface
{
    /**
     * Set a service in the container
     *
     * Services should be defined as closures
     *
     * @param string $id the service identifier
     * @param \Closure $service
     * @return static
     */
    public function set(string $id, \Closure $service);

    /**
     * Get a service
     *
     * @param string $id
     * @return mixed the associated service
     * @throws \InvalidArgumentException if the identifier is not defined
     */
    public function get(string $id);

    /**
     * Check if the given service is defined
     *
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool;
}
