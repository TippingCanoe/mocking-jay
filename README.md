# MockingJay

## Introduction

MockingJay provides platform agnostic, annotation-based, self-mocking POPOs.

## Usage

A demo is worth a thousand words, so let [demo.php](demo.php) stand as tribute. The output of this script is a mocked
instance of the `Foo` object.

``` php
object(Foo)#30 (9) {
  ["lorem"]=>
  string(46) "Illum earum reiciendis dolores id veniam eius."
  ["ipsum"]=>
  array(9) {
    [0]=>
    int(2)
    [1]=>
    int(5)
    [2]=>
    int(9)
    [3]=>
    int(1)
    [4]=>
    int(4)
    [5]=>
    int(1)
    [6]=>
    int(2)
    [7]=>
    int(6)
    [8]=>
    int(4)
  }
  ["dolor"]=>
  array(3) {
    [0]=>
    string(58) "Dolores consequatur sit voluptatem deserunt rem ut et est."
    [1]=>
    string(48) "Cupiditate officiis dolore temporibus veritatis."
    [2]=>
    string(46) "Numquam eum facere doloremque accusamus minus."
  }
  ["sit"]=>
  array(3) {
    [0]=>
    float(4.70929)
    [1]=>
    float(36016.47251)
    [2]=>
    float(1.735)
  }
  ["amit"]=>
  string(5) "AMIT!"
  ["consectetur"]=>
  string(18) "Daphney Ritchie IV"
  ["adipiscing"]=>
  object(Bar)#48 (2) {
    ["lorem"]=>
    NULL
    ["ipsum"]=>
    string(34) "Facere optio et suscipit nesciunt."
  }
  ["lacinia"]=>
  array(4) {
    [0]=>
    object(Bar)#59 (2) {
      ["lorem"]=>
      NULL
      ["ipsum"]=>
      string(30) "Quod iure laboriosam fuga aut."
    }
    [1]=>
    object(Bar)#54 (2) {
      ["lorem"]=>
      NULL
      ["ipsum"]=>
      string(29) "Aut beatae nulla sit facilis."
    }
    [2]=>
    object(Bar)#64 (2) {
      ["lorem"]=>
      NULL
      ["ipsum"]=>
      string(55) "Est ad consequatur quia veniam odio magnam soluta unde."
    }
    [3]=>
    object(Bar)#65 (2) {
      ["lorem"]=>
      NULL
      ["ipsum"]=>
      string(45) "Animi laudantium minus voluptatem omnis sunt."
    }
  }
  ["elit"]=>
  NULL
}
```

## Contact

Love it? Hate it? Want to make changes to it? Contact me at [@iainconnor](http://www.twitter.com/iainconnor) or
[iainconnor@gmail.com](mailto:iainconnor@gmail.com).