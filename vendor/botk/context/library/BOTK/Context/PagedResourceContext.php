<?php
namespace BOTK\Context;

use BOTK\Core\Singleton;
use BOTK\Context\PagedResourceContext as V;
    
class PagedResourceContext extends Context
{
    protected $defaults = array(
                'plabel'    => 'page',
                'pslabel'   => 'pagesize',
                'pagesize'  => 100
    );
    protected $pagesize,$pagenum,$pagedRequested;
       
    public function __construct(  $options = null)
    {
        if (!is_array($options)) $options = array();
        parent::__construct(array_merge($this->defaults,$options));
        
        $queryString= $this->ns(INPUT_GET);
        $options= $this->ns(self::LOCAL);
        
        $ps = $queryString->getValue($options->getValue('pslabel'), 0, FILTER_VALIDATE_INT);
        $p  = $queryString->getValue($options->getValue('plabel'), -1, FILTER_VALIDATE_INT);
        
        $this->pagedRequested = ($ps>0 || $p>=0);       
        $this->pagenum = max(0,$p);
        $this->pagesize= ($ps<1)?($options->getValue('pagesize')):$ps;
    }
    
        
    /*
     * Returns resource canonical uri with page info data in quesry string.
     * You can override request page info with passed paramethers
     */
    public function getSinglePageResourceUri( $pagenum = null, $pagesize=null)
    {
        if( is_null($pagenum) ) $pagenum  =$this->pagenum;
        if( is_null($pagesize)) $pagesize =$this->pagesize;
        
        //rebuild query string from already parsed _GET superglobal but do not override it.
        $vars = $_GET;
        $o=$this->ns(self::LOCAL);
        $vars[$o->getValue('plabel')]   = $pagenum;
        $vars[$o->getValue('pslabel')]  = $pagesize;
        
        return $this->getQueryStrippedUri() . '?'. http_build_query($vars);
    }


    public function getPagedResourceUri()
    {
        static $cachedUri = null;
        if (is_null($cachedUri)){  
            $uri = $this->getQueryStrippedUri();
            
            //rebuild query string from already parsed _GET superglobal but do not override it.
            $vars = $_GET;
            $o=$this->ns(self::LOCAL);
            unset($vars[$o->getValue('plabel')]);
            unset($vars[$o->getValue('pslabel')]);
            $cachedUri = count($vars)
                ?($uri.'?'. http_build_query($vars))
                :$uri;
        }
        
        return $cachedUri;
    }
    
    
   public function getQueryStrippedUri()
    {
        static $cachedUri = null;
        if (is_null($cachedUri)){  
            $cachedUri = $this->guessRequestCanonicalUri();
            //strip query string
            $cachedUri = preg_replace('/\?.*/','',$cachedUri);
        }
        
        return $cachedUri;
    }
    
    public function isSinglePageResource(){ return $this->pagedRequested;}
    public function getPageNum(){ return $this->pagenum;}
    public function getPageSize(){ return $this->pagesize;}
    
    /*
     * Some helpers
     */
    public function firstPageUri(){ return $this->getSinglePageResourceUri(0);}
    public function nextPageUri(){return $this->getSinglePageResourceUri($this->pagenum+1);}  
    public function prevPageUri(){ return $this->getSinglePageResourceUri(max(0,$this->pagenum-1));}  
}
