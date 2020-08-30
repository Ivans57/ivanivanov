<?php

namespace App\Http\Repositories;

use \App\Article;
//The repository below is required for converting BBCode to HTML 
//and processing these codes when opening an article.
use App\Http\Repositories\ArticleProcessingRepository;


class AboutMeArticle {
    public $articleTitle;
    public $articleBody;
}

class AboutMeRepository {
    
    public function getAboutMeArticle($current_page) {

        $about_me = Article::where('keyword', '=', $current_page)->first();
        
        //If there is no article with keyword "AboutMe", then need to add one.    
        
        $about_me_article = new AboutMeArticle();
        //The code below won't allow any error happen in case there is no article with keyword "AboutMe". 
        if (is_null($about_me)) {
            $about_me_article->articleTitle = __('keywords.'.$current_page);
            $about_me_article->articleBody = "";
        } else {
            //Because in About Me section will be displayed a normal article, its code processing should be applied.
            $about_me_processed_body = (new ArticleProcessingRepository())->articleCodeProcessing($about_me->article_body);
            $about_me_article->articleTitle = $about_me->article_title;
            $about_me_article->articleBody = $about_me_processed_body;
        }
        return $about_me_article;
    }
    
}
