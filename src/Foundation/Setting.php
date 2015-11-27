<?php

    namespace Amiya\Setting\Foundation;


    abstract class Setting
    {
        /**
         * The settings data.
         *
         * @var array
         */
        protected $settings = [ ];

        /**
         * Whether the store has changed since it was last loaded.
         *
         * @var boolean
         */
        protected $unsaved = false;

        /**
         * Whether the settings data are loaded.
         *
         * @var boolean
         */
        protected $loaded = false;

        /**
         * A random value
         *
         * @var
         */
        protected $randomDefaultValue;

        function __construct()
        {
            $this->randomDefaultValue = microtime( true );
        }

        /**
         * Get a specific key from the settings data.
         *
         * @param  string|array $key
         * @param  mixed        $default Optional default value.
         *
         * @return mixed
         */
        public function get( $key, $default = null )
        {
            $this->loadSettingsIfNotLoaded();

            $value = array_get( $this->settings, $key, $this->randomDefaultValue );

            if ( $this->randomDefaultValue !== $value ) {

                if ( $value === false ) {
                    return value( $default );
                }

                if(!is_string($value)){
                    return $value ;
                }

                switch ( strtolower( $value ) ) {
                    case 'true':
                    case '(true)':
                        return true;

                    case 'false':
                    case '(false)':
                        return false;

                    case 'empty':
                    case '(empty)':
                        return '';

                    case 'null':
                    case '(null)':
                        return null;
                }

                if ( starts_with( $value, '"' ) && starts_with( $value, '"' ) ) {
                    $value = substr( $value, 1, -1 );
                }

                return $value ;
            }

            return $default;
        }

        /**
         * Determine if a key exists in the settings data.
         *
         * @param  string $key
         *
         * @return boolean
         */
        public function has( $key )
        {
            $this->loadSettingsIfNotLoaded();

            return $this->randomDefaultValue !== array_get( $this->settings, $key , $this->randomDefaultValue );
        }

        /**
         * Put a specific key to a value in the settings data.
         *
         * @param string|array $key   Key string or associative array of key => value
         * @param mixed        $value Optional only if the first argument is an array
         */
        public function put( $key, $value = null )
        {
            $this->loadSettingsIfNotLoaded();
            $this->unsaved = true;

            if ( is_array( $key ) ) {
                foreach ( $key as $k => $v ) {
                    array_set( $this->settings, $k, $v );
                }
            } else {
                array_set( $this->settings, $key, $value );
            }
        }

        /**
         * Unset a key in the settings data.
         *
         * @param  string $key
         */
        public function forget( $key )
        {
            $this->unsaved = true;

            if ( $this->has( $key ) ) {
                array_forget( $this->settings, $key );
            }
        }

        /**
         * Unset all keys in the settings data.
         *
         * @return void
         */
        public function forgetAll()
        {
            $this->unsaved = true;
            $this->settings = [ ];
        }

        /**
         * Get all settings data.
         *
         * @return array
         */
        public function all()
        {
            $this->loadSettingsIfNotLoaded();

            return $this->settings;
        }

        /**
         * Save any changes done to the settings data.
         *
         * @return void
         */
        public function save()
        {
            if ( !$this->unsaved ) {
                // either nothing has been changed, or data has not been loaded, so
                // do nothing by returning early
                return;
            }

            $this->write();
            $this->unsaved = false;
        }

        /**
         * Check if the settings data has been loaded.
         */
        protected function loadSettingsIfNotLoaded()
        {
            if ( !$this->loaded ) {
                $this->settings = $this->load();
                $this->loaded = true;
            }
        }

        /**
         * Read the data from the store.
         *
         * @return array
         */
        abstract protected function load();

        /**
         * Write the data into the store.
         *
         * @param  array $data
         *
         * @return void
         */
        abstract protected function write();
    }
