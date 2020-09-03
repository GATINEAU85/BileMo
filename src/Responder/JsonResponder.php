<?php

namespace App\Responder;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class JsonResponder
{
    private $serializer;

    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }

    public function send(Request $request, $datas, int $status = 200, array $headers = [])
    {
        $datasJson = $this->serializer->serialize($datas, 'json');

        $response = new JsonResponse($datasJson, $status, $headers, true);
        $response->setEncodingOptions(JSON_UNESCAPED_SLASHES);

        if ($request->isMethodCacheable()) {
            $response->setCache([
                'etag' => md5($datasJson),
                'public' => true,
            ]);
            if ($response->isNotModified($request)) {
                $response->headers->addCacheControlDirective('Etag', 'valid');

                return $response;
            }
            $response->headers->addCacheControlDirective('Etag', 'invalid');
        }

        return $response;
    }
}