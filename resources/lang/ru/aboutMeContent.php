<?php


namespace App\Http\Controllers;

//This line was already here. Seems we don't realy need it, 
//but I will save this just in case.
//use Illuminate\Http\Request;

use App\AboutMeContent;


$aboutMeContents = AboutMeContent::all();
        
        $ru_aboutMeContents = array();
        
        foreach ($aboutMeContents as $aboutMeContent) {
            
        $ru_aboutMeContents [$aboutMeContent->keyword] = $aboutMeContent->content;
        
        }
        
        return $ru_aboutMeContents;
        

//Don't delete it! We will need it in a fufture for experiments!
/*
return [

    'AboutMeText' => '<p><span>Меня</span> зовут Иван Иванов. Мне 32 года. Я живу в Лондоне. Я энтузиаст и профессионал с хорошим опытом в разных отраслях (таких как энергетика, обслуживание клиентов и работа с людьми, разработка программного обеспечения, ремонт и обслуживание компьютеров). Среди своих сильных черт я могу выделить умение фокусироваться на достижении результата, ответственность, умение работать в команде, аналитический склад ума, умение понимать то, чего от меня хотят, а также возможность быстро обучаться. Мои интересы включают в себя технологии, науку и компьютеры.Ко всему прочему мне нравится путешествовать, изучать иностранные языки и культуру, общаться с интересными людьми.</p>',

];*/