<?php

namespace App\Utility;

/**
 * The 'CustomCurl' class allows to conveniently execute cURL requests with particular customizable options.
 */
class CustomCurl {
    private string $apiUrl;
    private int $verifyPeer;
    private int $verifyHost;
    private int $post;
    private int $returnTransfer;

    /**
     * The '__construct' method initializes properties with corresponding values, either defaults or passed as arguments.
     * 
     * @param string $apiUrl URL of the API to connect to.
     * @param int $verifyPeer Specifies (0 or 1) whether to verify the SSL
     * certificate of the remote server when making a request. (Default = 0, No verification)
     * @param int $verifyHost Specifies (0 or 1) whether to verify the SSL
     * certificate's hostname. (Default = 0, No verification)
     * @param int $post  Specifies (0 or 1) whether the request should be sent as a POST
     * request. (Default = 1, Send as POST)
     * @param int $returnTransfer Specifies (0 or 1) whether the response from the
     * API request should be returned as a string or not at all. (Default = 1, Send as string)
     * 
     * @return void
     */
    public function __construct(string $apiUrl, int $verifyPeer = 0, int $verifyHost = 0, int $post = 1, int $returnTransfer = 1) {

        $this->apiUrl = $apiUrl;
        $this->verifyPeer = $verifyPeer;
        $this->verifyHost = $verifyHost;
        $this->post = $post;
        $this->returnTransfer = $returnTransfer;
    }

    /**
     * The 'executeCurl' method executes a cURL request with specified headers & post fields and returns the
     * response.
     * 
     * @param array $headers An array of HTTP headers to be included in the request.
     * @param string $postFields Contains the data to be sent in the HTTP POST request. 
     * Although the content can generally be in various formats, for this eBay API an XML string is used.
     * 
     * @return string The response from the cURL request.
     * @throws \Exception If a cURL error occurs.
     */
    public function executeCurl(array $headers, string $postFields): string {

        $ch = curl_init();

        // Set necessary cURL options
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

        // Throw exception if there was an error during the cURL request
        if (curl_errno($ch)) {
            throw new \Exception('cURL ERROR: ' . curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }
}
