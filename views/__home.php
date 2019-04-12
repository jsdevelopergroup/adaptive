<!--<div class="main-carousel__area">

    <div class="main-carousel" id="slickSlider">

        <div class="main-carousel__item" style="background: #0F0">

            <div class="main-carousel__content">

                <div class="main-carousel__text">description</div>

                <div class="main-carousel__actions">

                    <a href="#" class="button">action button</a>

                </div>

            </div>

        </div>



    </div>

    <script type="text/javascript">

        $(document).ready(function(){

          $('#slickSlider').slick({

            dots: true,

            infinite: true,

            speed: 333,

            slidesToShow: 1,

            adaptiveHeight: true

          });

        });

    </script>

</div>-->

<!-- Popular Celebrity Posters -->

<div class="box_posters">

<h1><?=$home->categoryH1?></h1>

 <div class="box_posters__list clearfix">

   <?$n=0;

   $k=0;

   foreach($home->homeProducts as $item):

   $k++;

   if($n == 0):

      if($k != 13)echo '<h2>Popular <span>'.$home->getCategoryName($item['home_category_id']).'</span> Posters and Prints</h2>';

   endif;?>

   <div class="block_names">

        <div class="block_names__image">

          <a href="<?=$item['link_href']?>">

            <img src="<?=$item['home_picture_path']?>"

               title="<?=$item['link_title']?>"

                 alt="<?=$item['link_alt']?>"

               class="img-shadow" />

          </a>

        </div>
        


        <div class="block_names__title">

          <a href="<?=$item['link_href']?>">

            <span class="title_name"><?=$item['span_title']?></span>

          </a>

        </div>

   </div>

   <?

   $n++;

   if($n == 12):

      echo '</div><div class="box_posters__list clearfix">';

      $n = 0;

   endif;

   endforeach;?>

 </div>

<div class="article"><?=$home->categoryText?></div>

<br />

</div>