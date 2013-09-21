Authorize.net Component for CakePHP
=======================

Simple component for CakePHP that uses the Advanced Integration Method (AIM)for processing 'card not present' transactions in Authorize.net

_[Authorize.net Component Usage]_

This component currently only supports 2 basic authorize.net calls.  Authorize and Capture (AUTH_CAPTURE) and Credit (CREDIT).

### Configuration Options

Modify the $config variable with your secret login and transaction key you can obtain from Authorize.net

```php
  var $config = array(
		'x_login'		=> 'XXXXXX',
		'x_tran_key'	=> 'XXXXXX'
	);
```

### Authorize and Capture
Authorizes and Captures the credit card transaction.  This means that the amount is authorized by the card issuer and the transaction is complete.  There is also a call that will only authorize a transaction... this is not that.

```php
/**
	 * authorizes and captures a credit card transaction
	 * @param  array $data the data necessary to make the transaction
	 * @return array       the response from authorize.net
	 */
function auth_capture($data) {
  ...
}
```

The variable $data should be formatted the following way:

```php
  array(
				'Billing' 		=> array(
					'first_name'	=>'John',
					'last_name'		=>'Doe',
					'address'		=>'123 Main Street',
					'city'			=>'West Point',
					'state'			=>'NE',
					'zip_code'		=>'10010',
					'email'			=>'john.doe@email.com',
					'phone'			=>'801.555.1234'
				),
				'CreditCard' 	=> array(
					'number'		=>4100111122223333',
					'expiration'	=>'MMYY'
				),
				'Transaction' 	=> array(
					'amount'			=>'99.12',
					'description'		=> 'Purchase Tickets!',
					'invoice_number'	=> '51349684',
				)
			)
```

### Credit
Completely refunds a transaction.  A valid transaction ID from a successfully settled transaction must be passed.  This can only be called after the transaction has been settled.  Future versions will implement an action that will attempt to void a transaction and then credit if that attempt fails.

```php
/**
	 * refund an entire transaction. requires to pass the full transaction number
	 * @param  array $data the data necessary to make the transaction
	 * @return array       the response from authorize.net
	 */
function credit($data) {
  ...
}
```

The variable $data should be formatted the following way:

```php
  array(
				'trans_id' => '123456789', // a valid transaction ID of an original successfully settled transaction
				'credit_card' => '1355' //full credit card number or last four digits only here
			)
```

## License

The MIT License (MIT)

Copyright (c) 2013 LeGrande Jolley

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
