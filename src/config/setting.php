<?php

    return [

        /*
        |--------------------------------------------------------------------------
        | Storage Path
        |--------------------------------------------------------------------------
        |
        | This option determines the storage location of your setting file
        |
        */
        'path'     => storage_path(),

        /*
        |--------------------------------------------------------------------------
        | Settings file name
        |--------------------------------------------------------------------------
        |
        | This option option determines the name of the setting file you use
        | in your application to save the settings (It should be a json file)
        |
        */
        'filename' => 'settings.json',

        /*
        |--------------------------------------------------------------------------
        | Auto Save settings
        |--------------------------------------------------------------------------
        |
        | If this option is set to true the modified settings will be saved at
        | the end of every request
        |
        */
        'autosave' => true,
    ];