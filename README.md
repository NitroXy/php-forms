# Form builder for PHP

[![Build Status](https://travis-ci.org/NitroXy/php-forms.svg?branch=master)](https://travis-ci.org/NitroXy/php-forms)

<code>composer require nitroxy/php-forms</code>

```html
Form::from_object($user, function($f){
  $f->text_field('name', 'Name');
  $f->text_field('age', 'Age', ['type' => 'number', 'min' => 1]);
  $f->select(FormSelect::from_array($f, 'role', array('', 'Manager', 'Frobnicator', 'Developer'), 'Role'));
  $f->textarea('description', 'Description');
}, ['action' => $user->url, 'method' => 'patch']);
```

See [documentation](http://nitroxy.github.io/php-forms/) for examples and usage.
