<?php

namespace App\Controller;

use App\Entity\File;
use App\Repository\FileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/files")
 */

class FileController extends AbstractController
{
    private FileRepository $fileRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        FileRepository $fileRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->fileRepository = $fileRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/add", name="add", methods={"POST"})
     */
    public function add_file(Request $request): Response
    {
        $data = $request->files->get('file');

        if ($data) {
            $fileName = $data->getClientOriginalName();
            $fileDuplicate = $this->fileRepository->findBy(['name' => $fileName]);
            if ($fileDuplicate) {
                return $this->json(
                    [
                    'status' => 500,
                    'text' => "File with the same name has already been added"
                    ]
                );
            }
            $fileDirectory = $this->getParameter('kernel.project_dir') . '/var';

            $fileFullName = $fileDirectory . '/' . $fileName;

            $data->move($fileDirectory, $fileName);

            $file = new File();
            $file->setName($fileName);
            $file->setSize(filesize($fileFullName));

            $db = $this->getDoctrine()->getManager();
            $db->persist($file);
            $db->flush();

            return $this->json(
                [
                'status' => 200,
                'text' => "Adding was successful"
                ]
            );
        }

        return $this->json(
            [
            'status' => 400,
            'text' => "File was not added. Try again"
            ]
        );
    }


    /**
     * @Route("/get_files", name="get_all", methods={"GET"})
     */
    public function get_allFiles(Request $request, FileRepository $fileRepository): Response
    {
        $files = $fileRepository->findAll();
        $data = [];
        if ($files) {
            foreach ($files as $file) {
                $data[] = [
                    'name' => $file->getName(),
                    'size' => $file->getSize()
                ];
            }
            return $this->json(
                [
                'status' => 200,
                'files' => $data
                ]
            );
        }
        return $this->json(
            [
            'status' => 500,
            'text' => "Files were not found"
            ]
        );
    }

    /**
     * @Route("/{name}", name="download", methods={"GET"})
     */
    public function download_file(string $name): Response
    {
        $file = $this->fileRepository->findOneBy(['name' => $name]);
        if ($file) {
            $fileName = $this->getParameter('kernel.project_dir') . '/var/' . $name;
            $response = new BinaryFileResponse($fileName);
            $response->headers->set('Content-Type', 'text/plain');
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $name);
            return $response;
        }
        return $this->json(
            [
            'status' => 500,
            'text' => "Files were not found"
            ]
        );
    }

    /**
     * @Route("/{name}", name="delete", methods={"DELETE"})
     */
    public function delete_file(string $name): Response
    {
        $file = $this->fileRepository->findOneBy(['name' => $name]);
        if ($file) {
            $this->entityManager->remove($file);
            $this->entityManager->flush();
            unlink($this->getParameter('kernel.project_dir') . '/var/' . $name);
            return $this->json(
                [
                'status' => 200,
                'text' => "Files deleted"
                ]
            );
        }
        return $this->json(
            [
            'status' => 500,
            'text' => "Files were not found"
            ]
        );
    }
}
