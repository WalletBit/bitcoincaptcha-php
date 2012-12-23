<?php
/*
Copyright (C) 2012 by Kris

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

About
	This is a PHP library that handles calling bitcoinCAPTCHA.
*/

	/**
	 * Submits an HTTP POST to a bitcoinCAPTCHA server
	 * @param string $uri
	 * @param array $data
	 * @return array response
	 */
	function bitcoincaptcha_http_post($uri, $data)
	{
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $uri,
			CURLOPT_USERAGENT => 'bitcoinCAPTCHA/PHP',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => http_build_query($data)
		));

		return curl_exec($ch);
	}


	/**
	  * Calls an HTTP POST function to verify if the user's challenge was correct
	  * @param string $privkey
	  * @param string $remoteip
	  * @param string $challenge
	  * @return BitcoinCaptchaResponse
	  */
	function bitcoincaptcha_check($privkey, $remoteip, $challenge)
	{
		if ($privkey == null || $privkey == '' || $privkey == 'your_bitcoin_address') {
			die('To use bitcoinCAPTCHA you must get an bitcoin address from <a href="https://walletbit.com/">https://walletbit.com/</a>');
		}

		if ($remoteip == null || $remoteip == '') {
			die('For security reasons, you must pass the remote ip to bitcoinCAPTCHA');
		}

		$response = bitcoincaptcha_http_post('https://bitcoincaptcha.com/api/verify',
			array(
				'privatekey' => $privkey,
				'remoteip' => $remoteip,
				'challenge' => $challenge
			)
		);

		$response = explode("\r\n", $response);

		if ($response[0] == 1)
		{
			$bitcoincaptcha_response->isvalid = true;
		}
		else
		{
			$bitcoincaptcha_response->isvalid = false;
			$bitcoincaptcha_response->error = $response[1];
		}

		return $bitcoincaptcha_response;
	}
?>