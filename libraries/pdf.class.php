<?php
/*
pdfLayer class - Convert html to pdf
version 1.0 2/8/2016

API reference at https://pdflayer.com/documentation

Copyright (c) 2016, Wagon Trader

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
class html2pdf{
    
    //*********************************************************
    // Settings
    //*********************************************************
    
    //Your pdfLayer API key
    //Available at https://pdflayer.com/product
    private $apiKey = '4066922bc558dbe3d169e4fc151f8f28';
    
    //API endpoint
    //only needs to change if the API changes location
    private $endPoint = 'http://api.pdflayer.com/api/convert';
    
    //Secret Keyword defined in your pdfLayer dashboard
    //leave blank if you have not activated this feature
    private $secretKey = '';
    
    //API key/value pair params
    public $params = array();
    
    //response capture
    public $capture;
    
    /*
    method:  class construction
    usage:   html2pdf([string url]);
    params:  url = URL to web page
    
    The pdfLayer API requires a valid webpage URL or posted HTML to convert.
    
    returns: null
    */
    public function __construct($url=''){
        
        if( !empty($url) ){
            
            $this->params['document_url'] = $url;
            if( !empty($this->secretKey) ){
                
                $secret = md5($url.$this->secretKey);
                $this->params['secret_key'] = $secret;
                
            }
            
        }
        
    }
    
    /*
    method:  convertHTML
    usage:   convertHTML([redirect=false]);
    params:  redirect = redirect browser to api
    
    This method will query the api to convert the html to pdf.
    If redirect is set to true, browser will be redirected directly to api.
    
    returns: null
    */
    public function convertHTML($redirect=false){
        
        if( empty($this->params['document_url']) AND empty($this->params['document_html']) ){
            
            throw new Exception('A document must be provided!');
            
        }
        
        $request = $this->buildRequest();
        
        $postData = array();
        
        if( !empty($this->params['document_html']) ){
            
            $postData['document_html'] = $this->params['document_html'];
            
        }
        
        if( !empty($this->params['header_html']) ){
            
            $postData['header_html'] = $this->params['header_html'];
            
        }
        
        if( $redirect ){
            
            header('location: '.$request);
            exit;
            
        }
        
        $ch = curl_init($request);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($postData));
        $this->capture = curl_exec($ch);
        curl_close($ch);
        
        $response = json_decode($this->capture);
        if( is_object($response) ){
            
            throw new Exception($response->error->info);
            
        }
        
    }
    
    /*
    method:  downloadCapture
    usage:   downloadCapture([string fileName='']);
    params:  fileName = The name of the file written to disk
    
    This method will download the pdf to the client.
    
    returns: null
    */
    public function downloadCapture($fileName=''){
        
        $fileName = ( empty($fileName) ) ? 'capture' : $fileName;
        
        if( empty($this->capture) ){
            
            throw new Exception('No pdf has been captured');
            
        }
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        header('Content-Transfer-Encoding: binary');
        
        echo $this->capture;
        
    }
    
    /*
    method:  displayCapture
    usage:   displayCapture(void);
    params:  none
    
    This method will display the pdf to the browser.
    
    returns: null
    */
    public function displayCapture(){
        
        if( empty($this->capture) ){
            
            throw new Exception('No image has been captured');
            
        }
        
        header('Content-Type: application/pdf');
        
        echo $this->capture;
        
    }
    
    /*
    method:  buildRequest
    usage:   buildRequest(void);
    params:  none
    
    This method will build the api request url.
    
    returns: api request url
    */
    public function buildRequest(){
        
        $request = $this->endPoint.'?access_key='.$this->apiKey;
        
        foreach( $this->params as $key=>$value ){
            
            if( $key == 'document_url' ){
                
                $request .= '&document_url='.urlencode($value);
                
            }elseif( $key == 'document_html' ){
                //this will be posted
                
            }else{
                
                $request .= '&'.$key.'='.$value;
                
            }
            
        }
        
		//$request = $request.'&orientation=landscape';
		//$request = $request.'&page_width=1500&page_height=1000';
		
        return $request;
        
    }
    
    /*
    method:  setParam
    usage:   setParam(string key, string value);
    params:  key = key of the params key/value pair
             value =  value of the params key/value pair
    
    add or change the params key/value pair specified.
    
    returns: null
    */
    public function setParam($key,$value){
        
        $this->params[$key] = $value;
        
    }
}
?>
