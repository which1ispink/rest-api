<?php
namespace Which1ispink\API\Controller;

use Which1ispink\API\Core\DependencyInjection\ContainerInterface;
use Which1ispink\API\Core\Http\Request;
use Which1ispink\API\Core\Http\Response;
use Which1ispink\API\Core\Rest\AbstractController;
use Which1ispink\API\Entity\Character;
use Which1ispink\API\Service\CharacterService;

/**
 * Class CharacterController
 *
 * @author Ahmed Hassan <a.hassan.dev@gmail.com>
 */
class CharacterController extends AbstractController
{
    public function __construct(ContainerInterface $container, Request $request, Response $response)
    {
        parent::__construct($container, $request, $response);
    }

    /**
     * @return Character
     */
    public function fetch(): Character
    {
        /** @var CharacterService $characterService */
        $characterService = $this->get('character_service');
        $character = $characterService->find((int) $this->request->getRouteParameter(1));

        return $character;
    }

    /**
     * @return Character[]
     */
    public function fetchAll(): array
    {
        /** @var CharacterService $characterService */
        $characterService = $this->get('character_service');
        $characters = $characterService->findAll();

        return $characters;
    }

    /**
     * @return Character
     */
    public function create(): Character
    {
        /** @var CharacterService $characterService */
        $characterService = $this->get('character_service');
        $character = $characterService->create($this->request->getParameters());

        $this->response->setStatusCode(201);

        return $character;
    }

    /**
     * @return Character
     */
    public function update(): Character
    {
        /** @var CharacterService $characterService */
        $characterService = $this->get('character_service');
        $character = $characterService->update(
            $this->request->getParameters(),
            (int) $this->request->getRouteParameter(1)
        );

        return $character;
    }

    public function delete()
    {
        /** @var CharacterService $characterService */
        $characterService = $this->get('character_service');
        $characterService->kill((int) $this->request->getRouteParameter(1));

        $this->response->setStatusCode(204);
    }
}
