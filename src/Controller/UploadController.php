<?php

namespace App\Controller;

use App\Handler\ProductCreate;
use App\Message\UploadedFileProcessor;
use App\MessageHandler\UploadedFileProcessorHandler;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    #[Route('/api/upload', name: 'app_upload', methods: 'POST')]
    public function upload(Request $request, ProductCreate $handler, $uploadPath): Response
    {
        try {
            $file = $request->files->get( 'products');
            if (is_null($file)){
                return new JsonResponse(status: Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $ext = $file->guessExtension();
            if ($ext != 'xml'){
                return new JsonResponse(status: Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
            }

            $fileName = md5(uniqid()).'.'.$ext;
            $file->move($uploadPath, $fileName);

            $handler->handle($fileName);

            return new JsonResponse(status: Response::HTTP_OK);
        } catch ( Exception $e ) {
            return new JsonResponse([
                'status'=> 0,
                'exception' => $e->getMessage()
            ], status: Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/upload-tasks-count', name: 'upload_tasks_count', methods: 'GET')]
    public function getUploadTasksCount($uploadPath): JsonResponse
    {
        $count = count(glob("$uploadPath/*"));
        return new JsonResponse(data: $count,status: Response::HTTP_OK);
    }
}