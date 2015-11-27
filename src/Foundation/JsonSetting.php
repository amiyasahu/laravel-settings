<?php namespace Amiya\Setting\Foundation;

class JsonSetting extends Setting
{

    protected $fileSystem;

    protected $path;

    /**
     * @param $path
     * @param $filename
     */
    public function __construct( $app, $path = null )
    {
        parent::__construct( $app );
        $this->fileSystem = $app[ 'files' ];
        $this->setPath( $path ? : storage_path() . '/settings.json' );
        $this->loadSettingsIfNotLoaded();
    }

    /**
     * Set the path for the JSON file.
     *
     * @param string $path
     */
    public function setPath( $path )
    {
        if ( !$this->fileSystem->exists( $path ) ) {

            //File does not exists ,create a new one
            $result = $this->fileSystem->put( $path, json_encode( [ ], JSON_PRETTY_PRINT ) );

            if ( $result === false ) {
                throw new \InvalidArgumentException( "Could not write to {$path}." );
            }
        }

        if ( !$this->fileSystem->isWritable( $path ) ) {
            throw new \InvalidArgumentException( "{$path} is not writable." );
        }

        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    protected function load()
    {
        $contents = $this->fileSystem->get( $this->path );

        $data = json_decode( $contents, true );

        if ( $data === null ) {
            throw new \RuntimeException( "Invalid JSON in {$this->path}" );
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function write()
    {
        if ( !$this->settings ) {
            $this->settings = [ ];
        }

        $contents = json_encode( $this->settings, JSON_PRETTY_PRINT );

        $this->fileSystem->put( $this->path, $contents );
    }

}