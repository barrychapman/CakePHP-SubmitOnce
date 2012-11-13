# CakePHP-SubmitOnce

A plugin to prevent double submission of forms.

## More Detail

- Tokenizes forms by extension of the FormHelper
- Can be configured to display a flash message of your choosing
- Specify Model used in form
- Simple Setup!

### Setup

Extract this to the folder `APP/plugins/submit_once`


Include the helper

	var $helpers = array( 'SubmitOnce.SubmitOnce' );

Include the component

	var $components = array( 'SubmitOnce.SubmitOnce' );

Add the following to your controllers `beforeFilter()` and `beforeRender()` functions:

```php
	function beforeRender() {
		$this->SubmitOnce->create();
		parent::beforeRender();
	}

	function beforeFilter() {
		$this->SubmitOnce->process();
		parent::beforeFilter();
	}
```

