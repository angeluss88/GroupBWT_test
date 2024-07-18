<?php
require 'vendor/autoload.php';  

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

define('BIN_URL', 'https://lookup.binlist.net/');
// define('RATES_URL', 'https://api.exchangeratesapi.io/latest');
define('RATES_URL', 'https://api.apilayer.com/exchangerates_data/latest');

// There is a limit for this key = 100 queries per month. Replace it necessary
define('RATES_APIKEY', 'xzCX1kyIOoIgJ38Vpkf6Y2mxzKlXEbOR'); 

try {
    if(!isset ($argv[1])) {
        throw new Exception('Please provide filename');
    } 

    handleData($argv[1]);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

function handleData(string $filename, $hardCodedRates = false): void {
    try {
        $rates = getRates();
        
        $handle = fopen($filename, "r");
        if ($handle === false) {
            throw new Exception("Can\'t open the file!");
        }

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if ($line) {
                $data = json_decode($line, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    if(!isset($data['bin']) || !isset($data['amount']) || !isset($data['currency'])) {
                        throw new Exception('Please provide correct data in file: Transactions' . 
                        'should be provided each in it\'s own line in the input file, in JSON. ' . 
                        'Bin, amount, currrency should be provided for each transaction');
                    }

                    $binData = getBinData($data['bin']);

                    if(!$binData) {
                        continue;
                    }

                    $finalCoef = getFinalCoef($binData['country']['alpha2']);

                    $rate = $rates[$data['currency']];
                    if(!$rate) {
                        echo 'Can\'t get rates: no rates for this currency' . "\n";
                        continue;
                    }

                    $comission = $data['amount'];
                    if ($data['currency'] !== 'EUR' && $rate > 0) {
                        $comission /= $rate;
                    }

                    $comission = round($comission * $finalCoef, 2, PHP_ROUND_HALF_UP);

                    print_r($comission . PHP_EOL);
                } else {
                    echo "JSON error: " . json_last_error_msg() . "\n";
                }
            }
        }
    } finally {
        fclose($handle);
    }
}

function handleRequest(
    string $url, 
    string $method = 'GET', 
    array $headers = [], 
    int $timeout = 100, 
    bool $decodeJson = true
) {
    try {
        $client = new Client();
        $options = [
            'timeout' => $timeout,
        ];
        if(!empty($headers)) {
            $options['headers'] = $headers;
        }
        $response = $client->request($method, $url, $options);
        $body = $response->getBody()->getContents();

        if ($decodeJson) {
            $decodedBody = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Ошибка декодирования JSON: ' . json_last_error_msg());
            }
            return $decodedBody;
        }

        return $body;
    } catch (RequestException $e) {
        echo 'Ошибка запроса: ' . $e->getMessage() . "\n";

        return null;
    } catch (\RuntimeException $e) {
        echo $e->getMessage() . "\n";

        return null;
    }
}

function getBinData(string $bin): ?array {
    $url = BIN_URL . $bin;
    $binData = handleRequest($url);

    if($binData) {
        if(!isset($binData['country']) || !isset($binData['country']['alpha2'])) {
            // Sometimes it returns data with empty fields
            echo "Can\'t get data for bin: $bin correctly" . "\n";
            return null;
        }
    }

    return $binData;
}

function getFinalCoef(string $countryCode): float {
    // I'm not sure what this coef is, so I don't know how to optimize it.
    // In any case I'd like to see this process as a "black box"
    return isEu($countryCode) ? 0.01 : 0.02; 
}

function isEu(string $countryCode): bool {
    $euCodes = ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 
    'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK',];

    return in_array($countryCode, $euCodes);
}

function getRates(): ?array {
    $headers = [
        'apiKey' => RATES_APIKEY,
    ];
    
    $rates = handleRequest(RATES_URL, 'GET', $headers);

    if(!$rates || !$rates['success']) {
        $errMsg = 'Can\'t get rates: Unknown error';

        if($rates['error'] && $rates['error']['info']) {
            $errMsg = "Can\'t get rates: " . $rates['error']['info'];
        }
        throw new Exception($errMsg);
    }
    if(!$rates['rates']) {
        throw new Exception('Can\'t get rates: response format was changed!');
    }

    return $rates['rates'];
}
