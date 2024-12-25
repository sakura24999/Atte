<?php

return [
    'required' => ':attributeは必須項目です',
    'email' => ':attributeには有効なメールアドレスを入力してください',
    'unique' => ':attributeは既に使用されています',
    'min' => [
        'string' => ':attributeは:min文字以上で入力してください',
    ],
    'max' => [
        'string' => ':attributeは:max文字以下で入力してください',
    ],

    'attributes' => [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'name' => '名前',
    ],
];
