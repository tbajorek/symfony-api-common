<?php declare(strict_types=1);

namespace ApiCommon\Controller\Config;

use ApiCommon\Model\Config\Scope\ScopeProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontendConfigurationController extends AbstractController
{
    public function __construct(private readonly ScopeProvider $scopeProvider)
    //public function __construct()
    {
    }

    #[Route('/configs', name: 'get_frontend_configuration', methods: ['GET'])]
    public function getFrontendConfigurations(): Response
    {
        $this->scopeProvider->getAllScopes();
    }
}