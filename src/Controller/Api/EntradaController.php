<?php

namespace App\Controller\Api;

use App\Repository\EntradaRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntradaController extends AbstractController
{
    /**
     * @Route("/api/entrada", methods={"GET"})
     */
    public function index(Request $request, EntradaRepository $entradaRepository, PaginatorInterface $paginator): Response
    {
        $currentPage = $request->query->get('page', 1);
        $filter = $request->query->all();
        $query = $entradaRepository->getQueryByFilter($filter);
        $entradas = $paginator->paginate($query, $currentPage, 10);
        $resultado = [];
        foreach ($entradas as $entrada) {
            $resultado[] = [
                'id' => $entrada->getId(),
                'fecha' => $entrada->getFecha()->format('Y-m-d H:i:s'),
                'slug' => $entrada->getSlug(),
                'titulo' => $entrada->getTitulo(),
                'usuario' => $entrada->getUsuario()->getEmail(),
                'categoria' => $entrada->getCategoria()->getNombre()
            ];
        }
        return $this->json($resultado);
    }
}
