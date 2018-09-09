<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\Article;

//Need toperform a small research if we really need this. One more time find out about localiztion.
$articles = Article::all();
        
        $en_articles = array();
        
        foreach ($articles as $article) {
            
        $en_articles [$article->keyword] = $article->article_title;
        
        }
        
        return $en_articles;

