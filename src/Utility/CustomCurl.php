<?php

namespace App\Utility;

class CustomCurl {
    private $apiUrl;
    private $verifyPeer;
    private $verifyHost;
    private $post;
    private $returnTransfer;

    public function __construct(string $apiUrl, int $verifyPeer = 0, int $verifyHost = 0, int $post = 1, int $returnTransfer = 1) {

        $this->apiUrl = $apiUrl;
        $this->verifyPeer = $verifyPeer;
        $this->verifyHost = $verifyHost;
        $this->post = $post;
        $this->returnTransfer = $returnTransfer;
    }

    public function executeCurl(array $headers, string $postFields): string {

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_SSL_VERIFYPEER => $this->verifyPeer,
            CURLOPT_SSL_VERIFYHOST => $this->verifyHost,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => $this->post,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_RETURNTRANSFER => $this->returnTransfer,
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('cURL ERROR: ' . curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }
}
