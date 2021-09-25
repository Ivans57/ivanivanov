<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'Пароли в поле пароля и в поле его подтверждения не совпадают.',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'В поле :attribute должен быть адрес эл. почты.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => 'The :attribute field is required.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'В поле :attribute должно быть не больше :max знаков.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'В поле :attribute должно быть как минимум :min знаков.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'Необходимо заполнить поле :attribute.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'The :attribute format is invalid.', 
    
    //Additional validations added by developer
    'prohibited_characters' => 'Поле :attribute содержит запрещённые символы.',
    'keywords_prohibited_characters' => 'Поле :attribute содержит запрещённые символы.',
    'users_prohibited_characters' => 'Поле :attribute содержит запрещённые символы.',
    'articles_prohibited_characters' => 'Поле :attribute содержит запрещённые символы.',
    'space_check' => 'Поле :attribute не должно содержать пробелы.',
    'album_keyword_uniqueness_check' => 'Ключевое слово альбома не уникально.',
    'folder_keyword_uniqueness_check' => 'Ключевое слово папки не уникально.',
    'keyword_uniqueness_check' => 'Ключевое слово не уникально.',
    'username_uniqueness_check' => 'Имя пользователя не уникально.',
    'email_uniqueness_check' => 'Эл. адрес не уникален.',
    'picture_keyword_uniqueness_check' => 'Ключевое слово изображения не уникально.',
    'article_keyword_uniqueness_check' => 'Ключевое слово статьи не уникально.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'image_select' => [
            'required' => 'Пожалуйста, выберите изображение.',
            'image' => 'Выбранный файл не является изображением.',
            'mimes' => 'Загружаемый файл может иметь только расширения: jpg, jpeg, png, gif.',
            'max' => 'Загружаемое изображение не должно превышать :max килобайт.',
        ],
        'included_in_album_with_id' => [
            'item_has_directory' => 'Изображение должно быть сохранено внутри какого-то альбома.',
        ],
        'folder_id' => [
            'item_has_directory' => 'Статья должна быть сохранена внутри какого-то альбома.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => ['keyword' => __('keywords.Keyword'), 'text' => __('keywords.Text'), 'album_name' => __('keywords.AlbumName'),
                    'folder_name' => __('keywords.FolderName'), 'picture_caption' => __('keywords.PictureCaption'), 
                    'article_keyword' => __('keywords.ArticleKeyword'), 'article_title' => __('keywords.ArticleTitle'), 
                    'article_body' => __('keywords.ArticleText'), 'keyword' => __('keywords.Section'), 'name' => __('keywords.UserName'), 
                    'email' => __('keywords.Email'), 'password' => __('keywords.Password')],

];
