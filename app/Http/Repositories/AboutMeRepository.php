<?php

namespace App\Http\Repositories;


class MyPictureForView {
        public $keyWord;
        public $pictureCaption;
}

class AboutMeRepository {
    
    public function getMyPictureInfo($current_page){
        $my_picture = \App\Picture::where('keyword', $current_page)->first();
        
        $my_picture_info = new MyPictureForView();
        
        $my_picture_info->keyWord = $my_picture->keyword;
        $my_picture_info->pictureCaption = $my_picture->picture_caption;
        
        return $my_picture_info;
    }
    
}
