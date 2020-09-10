<?php
namespace App\Lib;

use Cake\Http\Client;

class DocketConnector {

    /**
     * @param $appUrl
     * @return string
     */

    public function login($appUrl = 'https://ecf.utd.uscourts.gov/cgi-bin/DktRpt.pl')
    {

        //if (isset($_COOKIE['pacerCookie'])) {
        //    return $_COOKIE['pacerCookie'];
        //}

        $cookies = null;
        $headers = $this->httpClient('https://pacer.login.uscourts.gov/cgi-bin/check-pacer-passwd.pl', [
            'loginid' => getenv('COURT_LOGIN'),
            'passwd'  => getenv('COURT_PASS'),
            'appurl'  => $appUrl
        ])->getHeaders();

        if (isset($headers['Set-Cookie'])) {
            $cookies = implode(' ', $headers['Set-Cookie']);
            setcookie('pacerCookie', $cookies);
        }

        return $cookies;
    }

    /**
     * @param $url
     * @param array $param
     * @param null $cookies
     * @return Client\Response|string
     */

    public function httpClient($url, $param = [], $cookies = null, $method = 'POST')
    {
        try {

            $http    = new Client();
            $options = [
                'headers' =>
                    [
                        'Referer'         => $url,
                        'Accept-Encoding' => 'gzip, deflate, br',
                        'Accept'          => '*/*',
                        'Connection'      => 'keep-alive',
                        'Host'            => 'ecf.utd.uscourts.gov',
                        'Content-Type'    => 'application/x-www-form-urlencoded',
                        'Cookie'          => $cookies,
                        'User-Agent'      => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) snap Chromium/79.0.3945.117 Chrome/79.0.3945.117 Safari/537.36'
                    ]
            ];

            switch ($method) {
                case 'POST' :
                    return  $http->post($url, $param, $options);

                case 'GET' :
                    return  $http->get($url, $param, $options);
            }


        } catch
        (\Exception $e) {
            return $e->getMessage();
        }
    }

}
