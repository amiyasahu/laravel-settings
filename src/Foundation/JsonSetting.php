<?php namespace Amiya\Setting\Foundation;

use Illuminate\Filesystem\Filesystem;

class JsonSetting extends Setting
{

    protected $files;

    protected $path;

    /**
     * @param $path
     * @param $filename
     */
    public function __construct( Filesystem $files, $path = null )
    {
        parent::__construct();
        $this->files = $files;
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
        if ( !$this->files->exists( $path ) ) {

            //File does not exists ,create a new one
            $result = $this->files->put( $path, json_encode( [], JSON_PRETTY_PRINT ) );

            if ( $result === false ) {
                throw new \InvalidArgumentException( "Could not write to {$path}." );
            }
        }

        if ( !$this->files->isWritable( $path ) ) {
            throw new \InvalidArgumentException( "{$path} is not writable." );
        }

        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    protected function load()
    {
        $contents = $this->files->get( $this->path );

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

        $this->files->put( $this->path, $contents );
    }

}