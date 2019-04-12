<?

class Navi {
    
    public $navigation;
    
    public function __construct($category,$cur_page,$no_of_paginations,$sortby,$delim){
        
       $previous_btn = true;
       $next_btn = true;
       $first_btn = true;
       $last_btn = true; 
  
       if($cur_page == 1):
          $previous_btn = false;
          $first_btn = false;
       endif; 
   
       if($cur_page == $no_of_paginations):
          $next_btn = false;
       endif;     
       
       if($cur_page + 4 > $no_of_paginations):
          $next_btn = false;
       endif;
       
       if($cur_page < 5):
          $previous_btn = false;
          $first_btn = false;
       endif;
 
        
    /* ---------------Calculating the starting and endign values for the loop----------------------------------- */
    if($cur_page >= 5) {
        
       $start_loop = $cur_page - 2;
       
       if($no_of_paginations > $cur_page + 2)
          $end_loop = $cur_page + 2;
       
       else if($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 4) {
          $start_loop = $no_of_paginations - 4;
          $end_loop = $no_of_paginations-1;
          
       } else {
          $end_loop = $no_of_paginations;
       }
    
    } else {
        
       $start_loop = 1;
       if($no_of_paginations > 5):
          $end_loop = 5;
       else:
          $end_loop = $no_of_paginations;
       endif;
      
    }
    
    $this->navigation .= "<ul class='navigation'>";
    // FOR ENABLING THE FIRST BUTTON
    
   // $this->navigation .= "<li class='info'>".$count." ".$names." | ".$no_of_paginations." pages</li>";
    
    // $msg .= "<li>Pagination:</li>";
    if($first_btn && $cur_page > 1):
       $this->navigation .= "<li><a href='".SITE_NAME.'/'.$category.$delim.'sortby='.$sortby."' class='active'>1</a></li>";
    /* elseif($first_btn):
       $this->navigation .= "<li><a href='".SITE_NAME.'/'.$category.'/'."' class='inactive'>First</a></li>"; */
    endif;

    // FOR ENABLING THE PREVIOUS BUTTON
    if($previous_btn && $cur_page > 1):
       $pre = $cur_page - 1;
       $this->navigation .= "<li><a href='".SITE_NAME.'/'.$category.$delim.'sortby='.$sortby."&page=".$pre."' class='active'>&#8592;</a></li>";
    /*elseif($previous_btn):
       $this->navigation .= "<li><a class='inactive'>Prev</a></li>";*/
    endif;
    
    //  echo $no_of_paginations; 
    
    
    
    for($i = $start_loop; $i <= $end_loop; $i++) {
        if($cur_page == $i){
           if($i == 1){
           
             if($no_of_paginations > 1){
                 $this->navigation .= "<li><div class='disable'>{$i}</div></li>";
             }
             
           
           }
           else{
              $this->navigation .= "<li><div class='disable'>{$i}</div></li>";
           }
           
           
        } else {
           if($i == 1){
              $this->navigation .= "<li><a href='".SITE_NAME.'/'.$category.$delim.'sortby='.$sortby."' class='active'>{$i}</a></li>";
           }
           else{
              $this->navigation .= "<li><a href='".SITE_NAME.'/'.$category.$delim.'sortby='.$sortby."&page=".$i."' class='active'>{$i}</a></li>";
           }
        }
    }
    
    // TO ENABLE THE NEXT BUTTON
    if($next_btn && $cur_page < $no_of_paginations):
       $nex = $cur_page + 1;
       $this->navigation .= "<li><a href='".SITE_NAME.'/'.$category.$delim.'sortby='.$sortby."&page=".$nex."' class='active'>&#8594;</a></li>";
    /* elseif($next_btn):
       $this->navigation .= "<li><a class='inactive'>Next</a>"; */
    endif;
    
    // TO ENABLE THE END BUTTON
    if($last_btn && $cur_page < $no_of_paginations):
    
      if($no_of_paginations > 5):
         $this->navigation .= "<li><a href='".SITE_NAME.'/'.$category.$delim.'sortby='.$sortby."&page=".$no_of_paginations."' class='active'>".$no_of_paginations."</a></li>";
      endif;
    
    elseif($last_btn):
    
    
      if($no_of_paginations > 1):
        $this->navigation .= "<li><div class='disable'>".$no_of_paginations."</div></li>";
      endif;   
        
    endif;

    $this->navigation .= "</ul>";
    
  }
}