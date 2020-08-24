<?php

namespace App\Http\Repositories;
use App\Http\Repositories\CommonRepository;
use ChrisKonnertz\BBCode\BBCode;
use App\Picture;
//We need the line below to change html code by php.
use simplehtmldom\HtmlDocument;

class FolderLinkForView {
    public $keyWord;
    public $name;
}
        
class FolderAndArticleForView {
    public $keyWord;
    public $caption;
    public $type;
}

class FolderAndArticleForViewFullInfoForPage {
    public $folder_name;
    public $head_title;
    public $foldersAndArticles;
    public $articleAmount;
    public $folderParents;
    public $folderNestingLevel;
    public $total_number_of_items;
    public $paginator_info;
}

class ArticleForView {
    public $article;
    public $articleParents;
}

class ArticlesRepository {
    
    public function getAllFolders($items_amount_per_page, $including_invisible){     
        if ($including_invisible) {
            $folder_links = \App\Folder::where('included_in_folder_with_id', '=', NULL)->orderBy('created_at','DESC')
                            ->paginate($items_amount_per_page);
        } else {
            $folder_links = \App\Folder::where('included_in_folder_with_id', '=', NULL)->where('is_visible', '=', 1)
                            ->orderBy('created_at','DESC')->paginate($items_amount_per_page);
        }      
        return $folder_links;
    }
    
    //We need the method below to clutter down the method in controller, which
    //is responsible for showing some separate album
    public function showFolderView($section, $page, $keyword, $items_amount_per_page, $main_links, $is_admin_panel, $including_invisible){
        
        $common_repository = new CommonRepository();
        //The condition below fixs a problem when user enters as a number of page some number less then 1
        if ($page < 1) {
            return $common_repository->redirect_to_first_page_multi_entity($section, $keyword, $is_admin_panel);          
        } else {
            $folders_and_articles_full_info = $this->getFolder($keyword, $page, $items_amount_per_page, $including_invisible);
            //We need to do the check below in case user enters a page number more tha actual number of pages
            if ($page > $folders_and_articles_full_info->paginator_info->number_of_pages) {
                return $common_repository->redirect_to_last_page_multi_entity($section, $keyword, $folders_and_articles_full_info->paginator_info->number_of_pages, $is_admin_panel);
            } else {                
                return $this->get_view($is_admin_panel, $keyword, $section, $main_links, $folders_and_articles_full_info, $items_amount_per_page);
            }
        }
    }
    
    //We need the method below to clutter down showFolderView method
    private function get_view($is_admin_panel, $keyword, $section, $main_links, $folders_and_articles_full_info, $items_amount_per_page) {
        if ($is_admin_panel) {
            return view('adminpages.adminfolder')->with([
                'main_links' => $main_links->mainLinks,
                'keywordsLinkIsActive' => $main_links->keywordsLinkIsActive,
                'headTitle' => $folders_and_articles_full_info->head_title,
                'folderName' => $folders_and_articles_full_info->folder_name,           
                'folders_and_articles' => $folders_and_articles_full_info->foldersAndArticles,
                'parents' => $folders_and_articles_full_info->folderParents,
                'nesting_level' => $folders_and_articles_full_info->folderNestingLevel,
                'pagination_info' => $folders_and_articles_full_info->paginator_info,
                'total_number_of_items' => $folders_and_articles_full_info->total_number_of_items,
                'items_amount_per_page' => $items_amount_per_page,
                'section' => $section,
                'parent_keyword' => $keyword
                ]);
        } else {
            return view('pages.folder')->with([
                'main_links' => $main_links,
                'headTitle' => $folders_and_articles_full_info->head_title,
                'folderName' => $folders_and_articles_full_info->folder_name,           
                'folders_and_articles' => $folders_and_articles_full_info->foldersAndArticles,
                'articleAmount' => $folders_and_articles_full_info->articleAmount,
                'parents' => $folders_and_articles_full_info->folderParents,            
                'pagination_info' => $folders_and_articles_full_info->paginator_info,
                'total_number_of_items' => $folders_and_articles_full_info->total_number_of_items,
                'items_amount_per_page' => $items_amount_per_page,
                'section' => $section
                ]);                   
        }
    }
    
    private function getFolder($keyword, $page, $items_amount_per_page, $including_invisible){
        //Here we take only first value, because this type of request supposed
        //to give us a collection of items. But in this case as keyword is unique
        //for every single record we will always have only one item, which is
        //the first one and the last one.
        //We are choosing the album we are working with at the current moment 
        $folder = \App\Folder::where('keyword', $keyword)->first();
        
        $nesting_level = \App\FolderData::where('items_id', $folder->id)->select('nesting_level')->firstOrFail();
        
        $included_articles = \App\Article::where('folder_id', $folder->id)->orderBy('created_at','DESC')->get();
        
        //Here we are calling method which will merge all articles and folders from selected folder into one array.
        if ($including_invisible) {
            $folders_and_articles_full = $this->get_included_folders_and_articles(
                    \App\Folder::where('included_in_folder_with_id', '=', $folder->id)
                    ->orderBy('created_at','DESC')->get(), $included_articles);
        } else {
            $folders_and_articles_full = $this->get_included_folders_and_articles(
                    \App\Folder::where('included_in_folder_with_id', '=', $folder->id)->where('is_visible', '=', 1)
                    ->orderBy('created_at','DESC')->get(), $included_articles);
        }
        
        //As we don't need to show all the items from the array above on the 
        //same page, we will take only first 20 items to show
        //Also we will need some variables for paginator
        
        //We need the object below which will contatin an array of needed folders 
        //and pictures and also some necessary data for pagination, which we will 
        //pass with this object's properties.
        $folders_and_articles_full_info = new FolderAndArticleForViewFullInfoForPage();
        
        $folders_and_articles_full_info->folderNestingLevel = $nesting_level->nesting_level;
        
        //Below we need to check if the folder has any parent folder.
        //If it does, Path Panel should be displayed
        //Need a bit to reorganize it.
        //We do not need property folderHasParent
        // We need property to keep all parents and this property can be checked in view
        if($folder->included_in_folder_with_id === NULL) {
            $folders_and_articles_full_info->folderParents = 0;
        }
        else {
            $folders_and_articles_full_info->folderParents = array_reverse($this->get_folders_and_articles_parents_for_view($folder->included_in_folder_with_id));
        }
        
        //We need this to know if we will have any article on the page.
        //Depending on if we have them or not, we will have some ceratin view of contents.
        $folders_and_articles_full_info->articleAmount = count($included_articles);        
        $folders_and_articles_full_info->folder_name = $folder->keyword;
        $folders_and_articles_full_info->head_title = $folder->folder_name;
        $folders_and_articles_full_info->total_number_of_items = count($folders_and_articles_full);
        
        //The following information we can have only if we have at least one item in selected folder
        if(count($folders_and_articles_full) > 0) {
            //The line below cuts all data into pages
            //We can do it only if we have at least one item in the array of the full data
            $folders_and_articles_full_cut_into_pages = array_chunk($folders_and_articles_full, $items_amount_per_page, false);
            $folders_and_articles_full_info->paginator_info = (new CommonRepository())->get_paginator_info($page, $folders_and_articles_full_cut_into_pages);
            //We need to do the check below in case user enters a page number more tha actual number of pages,
            //so we can avoid an error.
            if ($folders_and_articles_full_info->paginator_info->number_of_pages >= $page) {
                //The line below selects the page we need, as computer counts from 0, we need to subtract 1
                $folders_and_articles_full_info->foldersAndArticles = $folders_and_articles_full_cut_into_pages[$page-1];
            }
        } else {
            //As we need to know paginator_info->number_of_pages to check the condition
            //in showFolderView() method we need to make paginator_info object
            //and assign its number_of_pages variable. Otherwise we will have an error
            //if we have any empty folder
            $folders_and_articles_full_info->paginator_info = new Paginator();
            $folders_and_articles_full_info->paginator_info->number_of_pages = 1;
        }
        
        return $folders_and_articles_full_info;
    }
    
    public function getArticle($keyword) {
        
        $articles_full_info = new ArticleForView();
        
        $articles_full_info->article = \App\Article::where('keyword', '=', $keyword)->first();
        
        //Below before assigning a value to article_body, need to process it, as an article code is getting saved and stored as BBCode and
        //it is supposed to be converted to html.
        $articles_full_info->article->article_body = $this->article_code_processing($articles_full_info->article->article_body);
      
        $articles_full_info->articleParents = array_reverse($this->get_folders_and_articles_parents_for_view($articles_full_info->article->folder_id));
               
        return $articles_full_info;
    }
    
    //This function is required to process a code of an article, becaause it is getting created and stored in BBCode.
    //Before displaying an article on the website, its code should be converted to html.
    private function article_code_processing($article_body_code){       
        //BBCode converter which is being used here cannot convert list elements generated by current wysiwyg editor,
        //therefore some bbcode list tags convertion is required.
        $article_body_code = $this->convert_bbcode_list($article_body_code);
        
        //Adding smiley faces.
        $article_body_code = $this->add_smiley_faces($article_body_code);
              
        //Extracting from bbcode all images dimensions.
        $images_dimesions = $this->get_image_dimensions_from_bbcode($article_body_code);
        
        //Below we are adding additional tags to bbcode converter.
        //After adding additional elements bbcode is being converted to html.
        $article_body_code = $this->add_new_tags_to_bbcode_and_convert_to_html($article_body_code);
        
        $article_body_code = str_replace("</li><br/>","</li>",$article_body_code);
        $article_body_code = str_replace("<ul><br/>","<ul>",$article_body_code);
        $article_body_code = str_replace("<ol><br/>","<ol>",$article_body_code);
        $article_body_code = str_replace("</tr><br/>","</tr>",$article_body_code);
        $article_body_code = str_replace("</td><br/>","</td>",$article_body_code);
        $article_body_code = 
                str_replace("<blockquote>","<blockquote style='filter:brightness(55%);background:rgba(0,0,0,0.04);margin-top:5px;margin-bottom:5px;'>",
                        $article_body_code);
        
        $article_body_code = 
                str_replace("<table>","<table style='width:100%;border-collapse:collapse;margin-top:5px;margin-bottom:10px;'>",$article_body_code);
        
        $article_body_code = 
                str_replace("<td>","<td style='border:1px solid black;text-align:left;padding:8px;'>",$article_body_code);
        
        $article_body_code = 
                str_replace("<pre>","<pre style='margin-top:5px;margin-bottom:5px;'>",$article_body_code);
        
        //We cannot remove all <br>, because sometimes they are required, e.g. for lyrics.
        //Need to remove all <br> tags before and after <div>s as with them a text doesn't look nice.
        $article_body_code = str_replace("<br/>\n<div","<div",$article_body_code);
        $article_body_code = str_replace("</div><br/>","</div>",$article_body_code);
        
        //Need to remove margin-bottom from lists.
        $article_body_code = str_replace("<ol>","<ol style='margin-bottom:0px;'>",$article_body_code);
        $article_body_code = str_replace("<ul>","<ul style='margin-bottom:0px;'>",$article_body_code);
        
        //Need to remove all <br> tags before lists as with them a text doesn't look nice.
        $article_body_code = str_replace("<br/><ul>","<ul>",$article_body_code);
        $article_body_code = str_replace("<br/><ol>","<ol>",$article_body_code);
        
        //Need to remove all <br> tags after lists, tables, codes and quotes as with them a text doesn't look nice.
        $article_body_code = str_replace("</ul><br/>","</ul>",$article_body_code);
        $article_body_code = str_replace("</ol><br/>","</ol>",$article_body_code);       
        $article_body_code = str_replace("</table><br/>","</table>",$article_body_code);
        $article_body_code = str_replace("</pre><br/>","</pre>",$article_body_code);
        $article_body_code = str_replace("</blockquote><br/>","</blockquote>",$article_body_code);       
        
        $html_parser = new HtmlDocument();
        
        $html = $html_parser->load($article_body_code);
        
        $all_images = $html->find('.article-body-image');
        
        $images_sources = array();
        
        $images_names = array();
        
        $image_count = sizeof($all_images);
        
        for ($i = 0; $i < $image_count; $i++) {
            $images_sources[$i] = $all_images[$i]->src;
        }
        for ($i = 0; $i < $image_count; $i++) {
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
        //Below we need to make pictures floated by text if they are aligned to the left or to the right.
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
        return $html->save();
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
    
    //We need this function to make our own array which will contain all included
    //in some chosen folder folders and pictures
    private function get_included_folders_and_articles($included_folders, $articles){
        //After that we need to merge our albums and pictures to show them in selected album on the same page
        $folders_and_articles_full = array();       
        $included_folders_count = count($included_folders);
        
        for($i = 0; $i < $included_folders_count; $i++) {
            $folders_and_articles_full[$i] = new FolderAndArticleForView();
            $folders_and_articles_full[$i]->keyWord = $included_folders[$i]->keyword;
            $folders_and_articles_full[$i]->caption = $included_folders[$i]->folder_name;
            $folders_and_articles_full[$i]->type = 'folder';
        }           
        
        for($i = $included_folders_count; $i < count($articles)+$included_folders_count; $i++) {
            $folders_and_articles_full[$i] = new FolderAndArticleForView();
            $folders_and_articles_full[$i]->keyWord = $articles[$i-$included_folders_count]->keyword;
            $folders_and_articles_full[$i]->caption = $articles[$i-$included_folders_count]->article_title;
            $folders_and_articles_full[$i]->type = 'article';
        }
        
        return $folders_and_articles_full;
    }
    
    private function get_folders_and_articles_parents_for_view($id) {
        
        $parent_folder = \App\Folder::where('id', $id)->first();
        
        $parent_folder_for_view = new FolderLinkForView();
        
        $parent_folder_for_view->keyWord = $parent_folder->keyword;
        $parent_folder_for_view->name = $parent_folder->folder_name;
        
        $parent_folders_for_view = array();
        
        $parent_folders_for_view[] = $parent_folder_for_view;
        
        if ($parent_folder->included_in_folder_with_id === NULL) {
            return $parent_folders_for_view;
        }
        else {
            $folders_and_articles_parents_for_view = $this->get_folders_and_articles_parents_for_view($parent_folder->included_in_folder_with_id);
            foreach ($folders_and_articles_parents_for_view as $folders_and_articles_parent_for_view) {
                $parent_folders_for_view[] = $folders_and_articles_parent_for_view;
            }
            return $parent_folders_for_view;
        }
    }
    
    //This function is required when make a new article or edit existing one
    //to show the article's path.
    public function getArticlesParentsForPath($parent_folders_id) {
        return array_reverse($this->get_folders_and_articles_parents_for_view($parent_folders_id));;
    }
    
    //We need this to make a check for keyword uniqueness when adding a new
    //folder keyword or editing existing.
    public function get_all_folders_keywords() {
        
        $all_folders_keywords = \App\Folder::all('keyword');       
        $folders_keywords_array = array();
        
        foreach ($all_folders_keywords as $folder_keyword) {
            array_push($folders_keywords_array, $folder_keyword->keyword);
        }    
        return $folders_keywords_array;   
    }
}
