<?php

namespace AdamTorok96\GoogleReCaptcha;


use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class GoogleReCaptcha
{
    /**
     * Api url
     */
    const API_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Form parameter
     */
    const FORM_PARAMETER = 'g-recaptcha-response';

    /**
     * @var array $config
     */
    private $config;

    /**
     * @var array $attributes
     */
    private $attributes;

    /**
     * GoogleReCaptcha constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config       = $config;
        $this->attributes   = array();
    }

    /**
     * @param string $name
     * @param string|null $value
     */
    public function addAttribute(string $name, $value = null)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @return string
     */
    public function getCaptchaDom() : string
    {
        return '<div class="g-recaptcha" data-sitekey="' .  $this->config['site_key'] . '" ' . $this->getAttributesStr() . '></div>';
    }

    /**
     *
     */
    public function renderCaptcha()
    {
        echo $this->getCaptchaDom();
    }

    /**
     * @return string
     */
    public function getJsDom() : string
    {
        return '<script type="text/javascript" src="' . $this->getJsUrl() . '"></script>';
    }

    /**
     *
     */
    public function renderJs()
    {
        echo $this->getJsDom();
    }

    /**
     * @return string
     */
    public function getJsUrl() : string
    {
        return 'https://www.google.com/recaptcha/api.js';
    }

    /**
     * @param string $response
     * @param string $ip
     * @return bool
     */
    public function isValid(?string $response, ?string $ip) : bool
    {
        if( is_null($response) || empty($response) )
            return false;

        if( is_null($ip) || empty($ip) )
            return false;

        $client = new Client();

        try {
            $response = $client->post(GoogleReCaptcha::API_URL, [
                'form_params' => [
                    'secret'    => $this->config['secret_key'],
                    'response'  => $response,
                    'remoteip'  => $ip
                ]
            ]);
        } catch (Exception $exception) {
            return false;
        }

        if( $response->getStatusCode() !== 200 )
            return false;

        $json = json_decode($response->getBody()->getContents());

        return $json !== null && isset($json->success) && $json->success;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isValidRequest(Request $request) : bool
    {
        if( $request->has(GoogleReCaptcha::FORM_PARAMETER) === false )
            return false;

        return $this->isValid($request->get(GoogleReCaptcha::FORM_PARAMETER), $request->ip());
    }

    private function getAttributesStr() : string
    {
        $builder = [];

        foreach ($this->attributes as $key => $value) {
            $str = 'data-' . $key;

            if( is_null($value) === false ) {
                $str .= '="' . $value .'"';
            }

            array_push($builder, $str);
        }

        return implode(' ', $builder);
    }
}