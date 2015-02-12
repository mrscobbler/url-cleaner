<?php 

class Cleaner
{
	public $contentCleaned = false;
	public $contentLinkReport = array();
	public $internalDomains = array('example.com');
	public $excludeSubdomains = array('media');

	/* Set up objects for validation and modification */
	public function __construct(){
		$this->getObjects('Validate');
		$this->getObjects('Modify');
	}
	/*
	 * Clean content
	 * @param $content -- the content to be cleaned 
	 */
	public function clean($content){
		
		$htmlParser = new App_Util_SimpleHtmlDomParser();
		$htmlParser->load($content);
		$links = $htmlParser->find('a');
		$this->contentLinkReport = array();
		$this->contentCleaned = false;
		foreach($links as $link){
			$url = $link->href;
			if($this->isInternalUrl($url)){
				$urlReport = $this->validate($link);
				$urlReport = array_merge($urlReport, $this->modify($link));
				$urlReport['is_internal'] = true;
				$this->contentLinkReport[] = $urlReport;				
				if(!$urlReport['is_valid']){
					$this->contentCleaned = true;
					//Fix URL
					$link->href = $urlReport['correct_url'];
				}
				if(count($urlReport['params']) > 0){
					foreach($urlReport['params'] as $k => $v){
						$link->{$k} = $v;
					}
				}
			}else{
				$this->contentLinkReport[] = array('url'=>$url,'is_internal'=>false);				
			}
		}
		$correctedContent = $htmlParser->save();
		$htmlParser->clear();
		return $correctedContent;
	
	}
	/*
	 * Determine if the link is an internal link or an outside link
	 * @param $href -- the link to check
	 * 
	 */
	public function isInternalUrl($href){
		$urlParts = parse_url($href);
        if(isset($urlParts['host'])){
        	return in_array($urlParts['host'], $this->internalDomains);
        } elseif(isset($urlParts['path']) && strpos($urlParts['path'],'.com') === false){
            return true;
        } else {
            return false;
        }

	}
	/**
	 * Validate a given URL
	 * @param $url -- The URL to validate
	 * @return $report --- A report of the URL - array(is_valid => true/false, errors=> a string of errors, correct_url => the correct url)
	 */
	public function validate($link){
		$report['is_valid'] 	= true;
		$report['errors'] 		= array();
		$report['correct_url'] 	= '';
		$report['url']			= $link->href;
		
		foreach($this->validateObjs as $validationObj){
			$validator = new $validationObj($link);
			if(!$validator->isValid()){
				$report['is_valid'] 	= false;
				$report['errors'][] 	= $validator->getError();
				$url = $link->href	 	= $validator->fix();
			    echo "{$validationObj}: {$url}\n";
			} else {
				$url					= $link->href;
			}
		}
		
		$report['correct_url'] = $url;
		return $report;
	}
	
	
	
	/**
	 * Modify a given link
	 * @param $link - simplehtmldom object
	 * @return $report - a report on the link with new params
	 */
	public function modify($link){
		$report = array('params' => array());
		foreach($this->modifyObjs as $modificationObj){
			$modifier = new $modificationObj($link);
			if($modifier->requiresModification()){
				$report['params'] = $report['params'] + $modifier->modify();
			}
		}
		return $report;
	}

	/**
	 * Set up objects to manipulate a url
	 * @return void
	 */
	public function getObjects($type){
		$files = array();
		$iterator = new DirectoryIterator(dirname(__FILE__).DIRECTORY_SEPARATOR.$type);
		$property = strtolower($type).'Objs';

		foreach($iterator as $file){
		    if ($file->isDot() || $file->isDir()) {
		        continue;
		    }
		    $filename = $file->getFilename();

		    if ('Abstract.php' == $filename) {
		        continue;
		    }

		    if (!preg_match('/^([a-z_]+)\\.php$/i', $filename, $matches)) {
		        continue;
		    }

		    $files[] = 'App_Util_Url_' . $type . '_' . $matches[1];
		}
		
		$this->{$property} = $files;		
	}
	
	public function printLinkReport(){
		foreach($this->contentLinkReport as $link){
			if($link['is_internal']){
				if($link['is_valid']){
					echo "<span style='color:green'>".$link['url']."</span>";
				}else{
					echo "<span style='color:red'>".$link['url']."</span>&nbsp;<span>INVALID URL!".implode(',',$link['errors'])."&nbsp; FIXED: ".$link['correct_url']."</span>";
				}
				if(count($link['params']) > 0){
					echo 'Modified: '.var_export($link['params'], true);
				}	
			
			}else{
				echo "<span style='color:blue'>".$link['url']."</span>";
			
			}
			echo "<br/>";
		
		}
	
	}
		
}