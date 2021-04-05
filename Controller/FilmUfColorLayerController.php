<?php

namespace App\Controller\AdminCrud;

use App\Entity\FilmUfColorLayer;
use App\Form\FilmUfColorLayerType;
use App\Repository\FilmUfColorLayerRepository;
use App\Service\FileFilmColorUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/film/uf/color/layer")
 */
class FilmUfColorLayerController extends AbstractController
{
    /**
     * @Route("/", name="film_uf_color_layer_index", methods={"GET"})
     */
    public function index(FilmUfColorLayerRepository $filmUfColorLayerRepository): Response
    {
        return $this->render('admin/calculator_film/film_uf_color_layer/view_out_data_form_vars_value.html.twig', [
            'film_uf_color_layers' => $filmUfColorLayerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="film_uf_color_layer_new", methods={"GET","POST"})
     */

    public function new(Request $request, FileFilmColorUploader $fileFilmColorUploader): Response
    {
        $filmUfColorLayer = new FilmUfColorLayer();
        $form = $this->createForm(FilmUfColorLayerType::class, $filmUfColorLayer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //<--загрузка изображений через FileFilmColorUploader
            /** @var FileFilmColorUploader $ */
            $file = $form['image']->getData();
            if ($file) {
                $FileName = $fileFilmColorUploader->upload($file);
                $filmUfColorLayer->setImage($FileName);
            }
            //-------------------->


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($filmUfColorLayer);
            $entityManager->flush();

            return $this->redirectToRoute('film_uf_color_layer_index');
        }

        return $this->render('admin/calculator_film/film_uf_color_layer/new.html.twig', [
            'film_uf_color_layer' => $filmUfColorLayer,
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/{id}", name="film_uf_color_layer_show", methods={"GET"})
     */
    public function show(FilmUfColorLayer $filmUfColorLayer): Response
    {
        return $this->render('admin/calculator_film/film_uf_color_layer/show.html.twig', [
            'film_uf_color_layer' => $filmUfColorLayer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="film_uf_color_layer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, FilmUfColorLayer $filmUfColorLayer, FileFilmColorUploader $fileFilmColorUploader): Response
    {

        //проверка изображения для обновления
        $image = $filmUfColorLayer->getImage();
        if(!empty($image)){
            $filmUfColorLayer->setImage(
                new File($this->getParameter('file_film_uf_directory') . '/' . $image)
            );
        }
        //-------------->

        $form = $this->createForm(FilmUfColorLayerType::class,$filmUfColorLayer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // загрузка изображений
            /** @var FileFilmColorUploader $ */
            $file = $form['image']->getData();
            if ($file) {
                $FileName = $fileFilmColorUploader->upload($file);
                $filmUfColorLayer->setImage($FileName);
            } else {
                $filmUfColorLayer->setImage($image);
            }
           //-------------->
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('film_uf_color_layer_index');
        }

        return $this->render('admin/calculator_film/film_uf_color_layer/edit.html.twig', [
            'film_uf_color_layer' => $filmUfColorLayer,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="film_uf_color_layer_delete", methods={"DELETE"})
     */

    public function delete(Request $request, FilmUfColorLayer $filmUfColorLayer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$filmUfColorLayer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($filmUfColorLayer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('film_uf_color_layer_index');
    }
}
