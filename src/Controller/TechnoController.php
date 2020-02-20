<?php

namespace App\Controller;

use App\Entity\Techno;
use App\Form\TechnoType;
use App\Repository\TechnoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/admin/techno")
 */
class TechnoController extends AbstractController
{
    /**
     * @Route("/", name="techno")
     */
    public function index(TechnoRepository $technoRepository)
    {
        $technos = $technoRepository->findAll();
        return $this->render('techno/index.html.twig', [
            'technos' => $technos,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="techno_delete")
     */
    public function delete(Techno $techno,  EntityManagerInterface $manager)
    {
        $manager->remove($techno);
        $manager->flush();
        return $this->redirectToRoute('techno');
        
    }

    /**
     * @Route("/add", name="techno_add")
     */
    public function add(Request $request, EntityManagerInterface $manager)
    {
        $techno = new Techno();
        $form = $this->createForm(TechnoType::class, $techno);

        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){
            $logoFile = $form->get('logo')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($logoFile) {
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$logoFile->guessExtension();

                // Move the file to the directory where logos are stored
                try {
                    $logoFile->move(
                        $this->getParameter('logo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'logoFilename' property to store the PDF file name
                // instead of its contents
                $techno->setLogo($newFilename);
            }
            $manager->persist($techno);
            $manager->flush();
            return $this->redirectToRoute('techno');
        }
        
        return $this->render('techno/add.html.twig', [
            'form'=> $form->createView()
        ]);
        
    }

    /**
     * @Route("/edit/{id}", name="techno_edit")
     */
    public function edit(Techno $techno, Request $request, EntityManagerInterface $manager)
    {
        
        $form = $this->createForm(TechnoType::class, $techno);

        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){
                $logoFile = $form->get('logo')->getData();
                // this condition is needed because the 'brochure' field is not required
                // so the PDF file must be processed only when a file is uploaded
                if ($logoFile) {
                    $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$logoFile->guessExtension();
    
                    // Move the file to the directory where logos are stored
                    try {
                        $logoFile->move(
                            $this->getParameter('logo_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
    
                    // updates the 'logoFilename' property to store the PDF file name
                    // instead of its contents
                    $techno->setLogo($newFilename);
                }
            $manager->persist($techno);           
            $manager->flush();
            return $this->redirectToRoute('techno');
        }
        
        return $this->render('techno/edit.html.twig', [
            'form'=> $form->createView()
        ]);
        
    }
}
