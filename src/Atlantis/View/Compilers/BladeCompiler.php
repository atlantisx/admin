<?php namespace Atlantis\View\Compilers;

use Closure;
use Illuminate\Filesystem\Filesystem;

class BladeCompiler extends \Illuminate\View\Compilers\BladeCompiler {

    /**
     * Determine if the view at the given path is expired.
     *
     * @param  string  $path
     * @return bool
     */
    public function isExpired($path)
    {
        $compiled = $this->getCompiledPath($path);

        if ( ! $this->cachePath or ! $this->files->exists($compiled))
        {
            return true;
        }

        if( \Config::get('admin::admin.cache_view_disable',true) ){
            return true;
        }else{
            $lastModified = $this->files->lastModified($path);

            return $lastModified >= $this->files->lastModified($compiled);
        }
    }
}
