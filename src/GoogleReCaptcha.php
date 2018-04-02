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
    public function getCaptchaDom()
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
    public function getJsDom()
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
    public function getJsUrl()
    {
        return 'https://www.google.com/recaptcha/api.js';
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isValid(Request $request)
    {
        if( $request->has(GoogleReCaptcha::FORM_PARAMETER) === false )
            return false;

        $client = new Client();

        try {
            $response = $client->post(GoogleReCaptcha::API_URL, [
                'form_params' => [
                    'secret' => $this->config['secret_key'],
                    'response' => $request->get(GoogleReCaptcha::FORM_PARAMETER),
                    'remoteip' => $request->ip()
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

    private function getAttributesStr()
    {
        $builder = [];

        foreach ($this->attributes as $key => $value) {
            $str = $key;

            if( is_null($value) === false ) {
                $str .= '"' . $value .'"';
            }

            array_push($builder, $str);
        }

        return implode(' ', $builder);
    }
}