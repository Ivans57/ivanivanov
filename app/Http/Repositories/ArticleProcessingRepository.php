<?php

namespace App\Http\Repositories;
use ChrisKonnertz\BBCode\BBCode;
use App\Picture;
//We need the line below to change html code by php.
use simplehtmldom\HtmlDocument;

class ArticleProcessingRepository {
            
    //This function is required to process a code of an article, becaause it is getting created and stored in BBCode.
    //Before displaying an article on the website, its code should be converted to html.
    public function articleCodeProcessing($article_body_code){      
        //Oprations which are getting done below:
        //1. Extracting from bbcode all images dimensions.
        //2. BBCode converter which is being used here cannot convert list elements generated by current wysiwyg editor,
        //therefore some bbcode list tags convertion is required.      
        //3. Adding smiley faces.    
        //4. Adding additional tags to bbcode converter.
        //After adding additional elements bbcode is being converted to html.      
        //5. Processing an html. HTML code generated by BBCode->HTML converter
        //has some flaws like unnecessary tags and also some styles have to be added.
        //6. Processing the html code with a special parser as there
        //some operations that cannot be done with simple php functions.
        return $this->html_processing_with_parser($this->html_processing($this->add_new_tags_to_bbcode_and_convert_to_html
                             ($this->add_smiley_faces($this->convert_bbcode_list($article_body_code)))), $this->get_image_dimensions_from_bbcode($article_body_code));
    }
    
    //The function below is required for adding smiley faces to an article.
    private function add_smiley_faces($article_body_code) {  
        //There will be an array of smiley faces. Array will contain two elements arrays.
        //The first element will have s.f. abbreviation, the second element will have their dec. encoding.
        $smiley_faces = [[":)", "&#128578;"],[":angel:", "&#128519;"],[":angry:", "&#128544;"],["8-)", "&#128526;"],[":'(", "&#128552;"],
                         [":ermm:", "&#128527;"],[":D", "&#128513;"],["<3", "&#x1F9E1;"],[":(", "&#128577;"],[":O", "&#128558;"],
                         [":P", "&#128523;"],[";)", "&#128521;"],[":alien:", "&#128125;"],[":blink:", "&#128580;"],[":blush:", "&#128563;"],
                         [":cheerful:", "&#128539;"],[":devil:", "&#128520;"],[":dizzy:", "&#128565;"],[":getlost:", "&#128533;"],[":happy:", "&#128522;"],
                         [":kissing:", "&#128535;"],[":ninja:", "&#9865;"],[":pinch:", "&#128534;"],[":pouty:", "&#128528;"],[":sick:", "&#129314;"],
                         [":sideways:", "&#13025;"],[":silly:", "&#128579;"],[":sleeping:", "&#128564;"],[":unsure:", "&#128527;"],[":woot:", "&#128515;"],
                         [":wassat:", "&#129320;"]];
        
        for ($i = 0; $i < count($smiley_faces); $i++) {
            $article_body_code = str_replace($smiley_faces[$i][0],$smiley_faces[$i][1],$article_body_code);
        }     
        return $article_body_code;
    }
    
    //This method is getting used to convert bbcode list tags, generated by current wysiwyg editor, to more suitable format,
    //which can be converted to html by the converter is being used in this code.
    private function convert_bbcode_list($article_body_code) {
        $list_elements = [["[ul]", "[list]"],["[/ul]", "[/list]"],["[ol]", "[list=1]"],["[/ol]", "[/list]"]];
        
        for ($i = 0; $i < count($list_elements); $i++) {
            $article_body_code = str_replace($list_elements[$i][0],$list_elements[$i][1],$article_body_code);
        }     
        return $article_body_code;
    }
    
    //This function has been made to extract from bbcode all images dimensions.
    private function get_image_dimensions_from_bbcode($article_body_code) {
        //Extracting from bbcode all [img] tags with their attributes.
        preg_match_all("/\[img=.*?\]/i", $article_body_code, $image_sizes_attributes);      
        $images_dimesions = array();
        
        //Need to make an array containing images widths and heights.
        foreach ($image_sizes_attributes[0] as $image_size_attribute) {
            $image_size_attribute = preg_replace('/img=/i', '',trim($image_size_attribute,'[]'));
            $image_dimesions = explode("x", $image_size_attribute);
            //No need for any loop as diamensions always will be only two.
            $image_dimesions[0] = intval($image_dimesions[0]);
            $image_dimesions[1] = intval($image_dimesions[1]);
            array_push($images_dimesions, $image_dimesions);
        }       
        return $images_dimesions;
    }
    
    //This function is adding additional tags to bbcode converter, because by deafault not all tags are presented over there.
    //After adding additional elements bbcode is being converted to html.
    private function add_new_tags_to_bbcode_and_convert_to_html($article_body_code) {
        $bbcode = new BBCode();
                
        $bbcode->addTag('table', function($tag, &$html, $openingTag) {
            if ($tag->opening) {
                return '<table>';
            } else {
                return '</table>';
            }
        });
        $bbcode->addTag('tr', function($tag, &$html, $openingTag) {
            if ($tag->opening) {
                return '<tr>';
            } else {
                return '</tr>';
            }
        });
        $bbcode->addTag('td', function($tag, &$html, $openingTag) {
            if ($tag->opening) {
                return '<td>';
            } else {
                return '</td>';
            }
        });
        $bbcode->addTag('sub', function($tag, &$html, $openingTag) {
            if ($tag->opening) {
                return '<sub>';
            } else {
                return '</sub>';
            }
        });
        $bbcode->addTag('sup', function($tag, &$html, $openingTag) {
            if ($tag->opening) {
                return '<sup>';
            } else {
                return '</sup>';
            }
        });
        //This part should be disabled in BBCode file which is in Vendor\ChrisKonnertz\BBCode folder.
        $bbcode->addTag('img', function($tag, &$html, $openingTag) {
            if ($tag->opening) {
                return '<a href="#" class="article-body-image-link" data-fancybox="group" data-caption="any" title="any"><img class="article-body-image" src="';
            } else {
                return '" alt="alternate text" width="100" height="100"/></a>';
            }
        });          
        //On the last line, when returning a variable, converting regular text format to html.       
        return $bbcode->render($article_body_code);
    }
    
    //The function below is processing an html. HTML code generated by BBCode->HTML converter
    //has some flaws like unnecessary tags and also some styles have to be added.
    private function html_processing($article_body_code) {
        //Description what is being done below:
        //1. We cannot remove all <br>, because sometimes they are required, e.g. for lyrics.
        //Need to remove all <br> tags before and after <div>s as with them a text doesn't look nice.
        //2. Need to remove margin-bottom from lists.
        //3. Need to remove all <br> tags before lists as with them a text doesn't look nice.
        //4. Need to remove all <br> tags after lists, tables, codes and quotes as with them a text doesn't look nice.      
        $html_tags = [["</li><br/>", "</li>"],["<ul><br/>", "<ul>"],["<ol><br/>", "<ol>"],["</tr><br/>", "</tr>"],["</td><br/>", "</td>"],
                      ["<blockquote>", "<blockquote style='filter:brightness(55%);background:rgba(0,0,0,0.04);margin-top:5px;margin-bottom:5px;'>"],
                      ["<table>", "<table style='width:100%;border-collapse:collapse;margin-top:5px;margin-bottom:10px;'>"],
                      ["<td>", "<td style='border:1px solid black;text-align:left;padding:8px;'>"],
                      ["<pre>", "<pre style='margin-top:5px;margin-bottom:5px;'>"],["<br/>\n<div", "<div"],["</div><br/>", "</div>"],
                      ["<ol>", "<ol style='margin-bottom:0px;'>"],["<ul>", "<ul style='margin-bottom:0px;'>"],["<br/><ul>", "<ul>"],["<br/><ol>", "<ol>"],
                      ["</ul><br/>", "</ul>"],["</ol><br/>", "</ol>"],["</table><br/>", "</table>"],["</pre><br/>", "</pre>"],["</blockquote><br/>", "</blockquote>"]];
        
        for ($i = 0; $i < count($html_tags); $i++) {
            $article_body_code = str_replace($html_tags[$i][0],$html_tags[$i][1],$article_body_code);
        }     
        return $article_body_code;
    }
       
    //The funtion below is used to make more compicated operations with html code, 
    //which cannot be done by simple php functions.
    private function html_processing_with_parser($article_body_code, $images_dimesions) {
        //Description what is being done below:
        //1. Providing images with their dimensions on the web page and apply some necessary classes to them.     
        //2. Providing fonts necessary sizes.
        $html = $this->html_font_processing_with_parser($this->html_image_processing_with_parser((new HtmlDocument())->load($article_body_code), $images_dimesions));
        
        return $html->save();
    }
    
    //This funtion is used to provide images with their dimensions on the web page
    //and apply some necessary classes to them.
    private function html_image_processing_with_parser($html, $images_dimesions) {
        //Description what is being done below:
        //1. Assigning dimesions to images. 
        //2. Making pictures floated by text if they are aligned to the left or to the right.
              
        return $this->html_adding_styles_to_pictures($this->html_assigning_image_dimensions($html, $images_dimesions));
    }
    
    //This function is required to assign dimensions to images on website.
    private function html_assigning_image_dimensions($html, $images_dimesions) {
        $all_images = $html->find('.article-body-image');       
        $images_sources = array();        
        $images_names = array();      
        
        for ($i = 0; $i < sizeof($all_images); $i++) {
            $images_sources[$i] = $all_images[$i]->src;
            //We need to get file name and using it we can find picture caption.
            //As file's name always will be the last element of its path, 
            //we can easy access it after making it zero element by reversing an array.
            $file_path = array_reverse(explode("/", $images_sources[$i]));
            //Normally all articles pictures are supposed to be uploaded to the website albums first
            //and then to be used in articles. In this case a caption of the picture can be found in database.
            //If the picture is located on another website, we cannot get its caption from the database.
            //In this case just need to get its name from the link, removing its extension. That what is getting done in else case.
            $image_name = Picture::select('picture_caption')->where('file_name', '=', $file_path[0])->first();
            if (!empty($image_name)) {
                $images_names[$i] = $image_name->picture_caption;
            } else {
                $images_names[$i] = preg_replace("/\..*/", "", $file_path[0]);
            }
        }       
        $counter = 0;
        foreach($html->find('.article-body-image-link') as $element) {
            $element->href = $images_sources[$counter];
            $element->title = $images_names[$counter];
            //data-caption will be assigned using javascript as we cannot do it here due to error reasons.
            $counter++;
        }
        //Need to zero the counter below after the first usage.
        $counter = 0;
        foreach($html->find('.article-body-image') as $element) {
            $element->width = $images_dimesions[$counter][0];
            $element->height = $images_dimesions[$counter][1];
            $element->alt = $images_names[$counter];
            $counter++;
        }
        return $html;
    }
    
    //The function below is adding some necessary styles to pictures,
    //because pictures should be floated by text and some margins should be applied.
    private function html_adding_styles_to_pictures($html) {
        $links = $html->find('.article-body-image-link');      
        foreach($links as $link) {
            if (!empty($link->parent()->style)) {
                //Below need to extract the side from the string of element's style.
                $link_style_splitted = explode(": ", $link->parent()->style);
                if ($link_style_splitted[1] === "left") {
                    $link->parent()->style="float:".$link_style_splitted[1].";margin-right:15px;";
                } else if ($link_style_splitted[1] === "right") {
                    $link->parent()->style="float:".$link_style_splitted[1].";margin-left:15px;";
                }
            }      
        }
        return $html;
    }
    
    //The function below provides fonts necessary sizes.
    private function html_font_processing_with_parser($html) {
        //Below we need to make font size if user specified it.
        foreach($html->find('span[style^=font-size:]') as $element) {
            //Below extracting from the string line's dimensions.
            $element_style = explode(": ", $element->style);
            switch ($element_style[1]) {
                //Every size number (1-7 from editor) will match some normal font size.
                case 1:
                    $element->style = "font-size: 14px";
                    break;
                case 2:
                    $element->style = "font-size: 16px";
                    break;
                case 3:
                    $element->style = "font-size: 18px";
                    break;
                case 4:
                    $element->style = "font-size: 21px";
                    break;
                case 5:
                    $element->style = "font-size: 25px";
                    break;
                case 6:
                    $element->style = "font-size: 35px";
                    break;
                case 7:
                    $element->style = "font-size: 45px";
                    break;
                default:
                    $element->style = "font-size: 17px";
            }          
        }
        return $html;
    }
}
