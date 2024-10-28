<?php
// src/Controller/UploaderController.php
namespace App\Controller;

use App\Service\FileMailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\FileMailerType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderController extends AbstractController
{
    private $params;
    public function __construct(ParameterBagInterface $parameterBagInterfaces) {
        $this->params = $parameterBagInterfaces;
    }
    #[Route('/file/uploader', name: 'fileuploader')]
    public function myPage(Request $request, FileMailerService $mailerService): Response
    {
        $pdf_form = $this->createForm(FileMailerType::class);
        $pdf_form->handleRequest($request);
        if ($pdf_form->isSubmitted() && $pdf_form->isValid()) {
            $data = $pdf_form->getData();
            foreach ($data['PDF_file'] as $file) {
                $mailerService->sendEmail($this->params->get('automatmail'), $this->params->get('recevemail'), 'Facture Fournisseur PDF', 'test!!!', $file);
            }
        }
        return $this->render('FileUploader.html.twig', [
            'pdf_form'=> $pdf_form->createView(),
        ]);
    }
}
