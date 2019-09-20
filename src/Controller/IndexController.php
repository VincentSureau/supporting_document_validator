<?php

namespace App\Controller;

use App\Form\ImageType;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET","POST"})
     */
    public function index(Request $request)
    {
        $form = $this->createForm(ImageType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form['image']->getData();

            $data = array_diff($form->getData(), [$image]);
            $ocr = new TesseractOCR($image->getPathname());
            $ocr->lang('fra');

            $imageText = $ocr->run();

            if(empty($imageText)) {
                $message = [
                    'status' => 'warning',
                    'content' => "Aucun texte n'a pu être lu sur l'image"
                ];
            } else {
                $message = [
                    'status' => 'info',
                    'content' => "Text lu sur l'image:\n$imageText"
                ];
            }

            $this->addFlash($message['status'], $message['content']);

            if(!empty($imageText)) {
                foreach($data as $param => $elem) {
                    if(stristr($imageText, $elem)) {
                        $this->addFlash('success', "$param est bien dans l'image uploadée");
                    } else {
                        $this->addFlash('danger', "$param n'est pas dans l'image uploadée");
                    }
                }
            }
            
        }

        return $this->render('index/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
