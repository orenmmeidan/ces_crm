<?
class ContentLoader extends Query{
 	
	private $url_string; // the url_string
	public $url_array= array(); // the array of the elements in the url_string
	public $language; //the id of the language loaded
	public $language_name; //the name of hte language loaded. will be used in the <body> tag as a class
	public $content_loaded= array();
	public $page_id; // the id of the page which will be used in the <body> tag and also to define which page are we on
	public $site_skin;  // the skin name of the site
	public $process_file; //the name fo the file which will process the script
	public $members; //is it a members section or not - boolean

   public function __construct($url, $debug=false){
        
        Query::__construct($debug);
		$this->url_string=str_replace(" ", "-", $url);
		$this->url_string=$this->utf8_urldecode($url);
		//remove GET vars
		if ($pos = strpos($this->url_string, "?")) $this->url_string = substr($url, 0, $pos);
		//remove first slash
		if (substr($this->url_string,0,1)=="/") $this->url_string = substr($url, 1);
		//echo $this->url_string;
		$this->url_array=explode("/", $this->url_string);
		//remove the folder name if it's on the local
		if($this->url_array[0]==DIR_NAME)array_shift($this->url_array);
		//print_r($this->url_array);
		
		//if url_array[0] then it's the home page
		if ($this->url_array[0] == "")$this->url_array[0] = "home";
		
		//extract the language
		$this->extract_language();
		//extract the contnet
		$this->extract_content(false);
		//find the chosen skin
		$this->get_site_skin(false);
    }
    function utf8_urldecode($str) {
		$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
		//return html_entity_decode($str,null,'UTF-8');;
		return $str;
	}
	function get_site_skin($debug){
		$this->site_skin = $this->GetSetting("site_skin",null,$debug);	
	}
	function extract_language(){
		
		if (count($this->url_array) > 0){
			$maybe_lang = $this->url_array[0];
			$result = $this->find_value(PREFIX."languages", "id",  "name", $maybe_lang,false );
			if($result !=""){
				$this->language =$result;
				array_shift($this->url_array);
				}
			else{
				$this->language = $this->GetSetting("default_language",null,true);
				}
			
			}
		else{
		$this->language = $this->GetSetting("default_language",null,true);	
		}
		//echo $this->language;	
		$this->language_name = $this->find_value(PREFIX."languages", "name",  "id", $this->language,false );
		//echo $this->language_name;
	}
	
	function extract_content($debug){
		// find if plugin
		$search_url = $this->escape_smart($this->url_array[0]);
		$result = $this->getRow(PREFIX."plugins", "url='$search_url' AND language=".$this->language,$debug);
		if($result){
		   //echo "it's a plugin";
		   $this->content_loaded = $result;
		   $this->page_id = $this->content_loaded['name'];
		   $this->process_file = $this->content_loaded['include_dir'];
		   $this->members = $this->content_loaded['members'];
		   //print_r($this->content_loaded);
		}
		else{
		   // find if content page
		   $result = $this->getRow(PREFIX."content", "`url`='$search_url' AND `language`=".$this->language." AND `enabled`='1' AND `published` ='1'",$debug);
		    if($result){
		         //echo "it's a content page";
		         $this->content_loaded = $result;
				 $this->page_id = $this->content_loaded['url'];
				 $this->process_file = "content";
		        //print_r($this->content_loaded);
			}
			else{
				//it's a page not found (404)
				$result = $this->getRow(PREFIX."content", "`url`='404' AND `language`=".$this->language." AND `published` ='1'",$debug);
				if($result){
					 $this->content_loaded = $result;
					 $this->page_id = $this->content_loaded['url'];
					 $this->process_file = "content";
				}
			}
		}
	}
	function load_plugin_js_files($files_arr_js){
		$directory = SITE_DIR."plugins/".$this->process_file."/js/";
		if(file_exists  ($directory))
		 {
			//$files_arr = dirList ($directory) ;
			for($i=0;$i<sizeof($files_arr_js);$i++)
			{
			if(!is_dir($directory . $files_arr_js[$i]) && file_exists($directory . $files_arr_js[$i]))
			{
			echo "<script type=\"text/javascript\" src=\"".SITE_URL."plugins/".$this->process_file."/js/".$files_arr_js[$i]."\"></script>\n\r";
			}
			}
		 }
	}
	function load_plugin_css_files($files_arr_css){
		$directory = SITE_DIR."plugins/".$this->process_file."/css/";
		if(file_exists  ($directory))
		 {
			//$files_arr = dirList ($directory) ;
			for($i=0;$i<sizeof($files_arr_css);$i++)
			{
			if(!is_dir($directory . $files_arr_css[$i]) && file_exists($directory . $files_arr_css[$i]))
			{
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".SITE_URL."plugins/".$this->process_file."/css/".$files_arr_css[$i]."\" />\n\r";
			}
			}
		 }
	}
	function load_jquery_files($jquery_plugin_arr){
		 for($i=0;$i<sizeof($jquery_plugin_arr[2]);$i++)
			{
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".SITE_URL.$jquery_plugin_arr[0][0] . $jquery_plugin_arr[2][$i]."\" />\n\r";
			}
			 for($i=0;$i<sizeof($jquery_plugin_arr[1]);$i++)
			{
			echo "<script type=\"text/javascript\" src=\"".SITE_URL.$jquery_plugin_arr[0][0] . $jquery_plugin_arr[1][$i]."\"></script>\n\r";
			}
	}
}

?>