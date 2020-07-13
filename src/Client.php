<?php
namespace BureauPartners\MultiPost;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;

class Client
{
    protected $hostname     = 'https://api.multipost.com/api';
    protected $bearer_token = null;
    protected $client       = null;
    protected $version      = null;
    protected $os           = null;

    const HTTP_GET     = 'GET';
    const HTTP_HEAD    = 'HEAD';
    const HTTP_DELETE  = 'DELETE';
    const HTTP_OPTIONS = 'OPTIONS';
    const HTTP_PUT     = 'PUT';
    const HTTP_POST    = 'POST';
    const HTTP_PATCH   = 'PATCH';

    const ENVELOPE_C5 = 'C5';
    const ENVELOPE_C4 = 'C4';

    public function __construct($email, $password, $hostname = null)
    {
        if ($hostname !== null) {
            $this->hostname = $hostname;
        }
        if ($this->bearer_token == null) {
            $this->requestBearerToken($email, $password);
        }
        $this->version = phpversion();
        $this->os      = PHP_OS;
    }

    public function isConnected()
    {
        if ($this->bearer_token !== null) {
            return true;
        } else {
            return false;
        }
    }

    public function requestBearerToken($email, $password)
    {
        $body = [
            'email'    => $email,
            'password' => $password,
        ];
        $response = $this->request(Client::HTTP_POST, '/login', $body);
        if (!is_array($response)) {
            return false;
        }
        $this->bearer_token = $response['access_token'];
        return true;
    }

    protected function getClient()
    {
        if (!$this->client) {
            $this->client = new GuzzleClient([
                RequestOptions::VERIFY  => true,
                RequestOptions::TIMEOUT => 30,
            ]);
        }
        return $this->client;
    }

    protected function request($method = null, $path = null, $body = null)
    {
        $client  = $this->getClient();
        $options = [
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::HEADERS     => [
                'User-Agent'    => 'MultiPost Client (PHP Version: ' . $this->version . ', OS: ' . $this->os . ')',
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $this->bearer_token,
            ],
        ];

        if (!empty($body)) {
            $cleanParams = array_filter($body, function ($value) {
                return $value !== null;
            });
            switch ($method) {
                case Client::HTTP_GET:
                case Client::HTTP_HEAD:
                case Client::HTTP_DELETE:
                case Client::HTTP_OPTIONS:
                    $options[RequestOptions::QUERY] = $cleanParams;
                    break;
                case Client::HTTP_PUT:
                case Client::HTTP_POST:
                case Client::HTTP_PATCH:
                    $options[RequestOptions::JSON] = $cleanParams;
                    break;
            }
        }
        $response      = $client->request($method, $this->hostname . $path, $options);
        $response_code = $response->getStatusCode();
        if ($response_code == 200) {
            return json_decode($response->getBody(), true);
        } else {
            return $response->getBody()->getContents();
        }
    }
    
    //Clients
    public function getClients($company_uuid)
    {
        return $this->request(Client::HTTP_GET, '/'.$company_uuid.'/clients');
    }

    public function createClient($company_uuid, $data)
    {
        return $this->request(Client::HTTP_POST, '/'.$company_uuid.'/clients', $data);
    }

    //Companies
    public function getCompanies()
    {
        return $this->request(Client::HTTP_GET, '/company');
    }

    public function createCompany($data)
    {
        return $this->request(Client::HTTP_POST, '/company', $data);
    }

    public function getCompany($company_uuid)
    {
        return $this->request(Client::HTTP_GET, '/company/'.$company_uuid);
    }

    public function deleteCompany($company_uuid)
    {
        return $this->request(Client::HTTP_DELETE, '/company/'.$company_uuid);
    }

    public function listUsers($company_uuid)
    {
        return $this->request(Client::HTTP_GET, '/'.$company_uuid.'/users');
    }

    public function addUser($company_uuid, array $users)
    {
        return $this->request(Client::HTTP_POST, '/'.$company_uuid.'/users', $users);
    }

    //Documents
    public function createDocument($company_uuid, $data)
    {
        return $this->request(Client::HTTP_POST, '/'.$company_uuid.'/document', $data);
    }

    public function getDocument($company_uuid, $document_uuid)
    {
        return $this->request(Client::HTTP_GET, '/'.$company_uuid.'/document/'.$document_uuid);
    }

    //Envelopes
    public function getEnvelopes($company_uuid)
    {
        return $this->request(Client::HTTP_GET, '/' . $company_uuid . '/envelope');
    }

    public function createEnvelope($company_id, $name, $description, $envelope_details = Client::ENVELOPE_C5, $design_file_contents = null)
    {
        if (is_array($envelope_details)) {
            $envelope = [
                'name'        => $name,
                'description' => $description,
            ];
            $envelope = array_merge($envelope, $envelope_details);
        } elseif ($envelope_details === Client::ENVELOPE_C5) {
            $envelope = [
                'name'               => $name,
                'description'        => $description,
                'width'              => 229,
                'height'             => 162,
                'weight'             => 8,
                'max_pages'          => 9,
                'window_width'       => 110,
                'window_height'      => 40,
                'window_left_margin' => 20,
                'window_top_margin'  => 50,
            ];
        } elseif ($envelope_details === Client::ENVELOPE_C4) {
            $envelope = [
                'name'               => $name,
                'description'        => $description,
                'width'              => 229,
                'height'             => 324,
                'weight'             => 8,
                'max_pages'          => 30,
                'window_width'       => 110,
                'window_height'      => 40,
                'window_left_margin' => 20,
                'window_top_margin'  => 50,
            ];
        } else {
            return false;
        }
        if ($design_file_contents !== null) {
            $envelope_design = [
                'design_file_contents' => base64_encode($design_file_contents),
            ];
            $envelope = array_merge($envelope, $envelope_design);
        }
        return $this->request(Client::HTTP_POST, '/' . $company_id . '/envelope', $envelope);
    }

    public function getEnvelope($company_uuid, $envelope_uuid)
    {
        return $this->request(Client::HTTP_GET, '/'.$company_uuid.'/envelope/'.$envelope_uuid);
    }

    public function deleteEnvelope($company_uuid, $envelope_uuid)
    {
        return $this->request(Client::HTTP_DELETE, '/'.$company_uuid.'/envelope/'.$envelope_uuid);
    }

    public function orderEnvelope($company_uuid, $envelope_uuid, $quantity)
    {
        return $this->request(Client::HTTP_POST, '/'.$company_uuid.'/envelope/'.$envelope_uuid.'/order', ['quantity' => $quantity]);
    }

    public function getEnvelopeOrder($company_uuid, $order_uuid)
    {
        return $this->request(Client::HTTP_POST, '/'.$company_uuid.'/envelope/order/'.$order_uuid.'');
    }

    //Mailbox
    public function getMailboxes($company_uuid)
    {
        return $this->request(Client::HTTP_GET, '/'.$company_uuid.'/mailbox');
    }

    public function getMailbox($company_uuid, $mailbox_uuid)
    {
        return $this->request(Client::HTTP_GET, '/'.$company_uuid.'/mailbox/'.$mailbox_uuid);
    }

    //Paper
    public function getPaperTypes()
    {
        return $this->request(Client::HTTP_GET, '/paper');
    }

    public function getPaperType($paper_type_uuid)
    {
        return $this->request(Client::HTTP_GET, '/paper/'.$paper_type_uuid);
    }

    //Print orders
    public function createPrintOrder($company_uuid, array $data)
    {
        return $this->request(Client::HTTP_POST, '/'.$company_uuid.'/print', $data);
    }

    public function getPrintOrder($company_uuid, $print_order_uuid)
    {
        return $this->request(Client::HTTP_GET, '/'.$company_uuid.'/print/'.$print_order_uuid);
    }

    public function cancelPrintOrder($company_uuid, $print_order_uuid)
    {
        return $this->request(Client::HTTP_DELETE, '/'.$company_uuid.'/print/'.$print_order_uuid);
    }

    //Print processing types
    public function getPrintProcessingTypes()
    {
        return $this->request(Client::HTTP_GET, '/processing');
    }

    public function getPrintProcessingType($print_processing_uuid)
    {
        return $this->request(Client::HTTP_GET, '/processing/'.$print_processing_uuid);
    }

    //Shipping
    public function getShippingProducts()
    {
        return $this->request(Client::HTTP_GET, '/shipping');
    }

    public function getShippingProduct($shipping_product_uuid)
    {
        return $this->request(Client::HTTP_GET, '/shipping/'.$shipping_product_uuid);
    }
}
