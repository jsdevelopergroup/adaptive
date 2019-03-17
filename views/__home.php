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

<h1>Posters, photos | mousepads, t-shirts | magnets, puzzles | pillows, mugs | Calendar 2019 | Phone cases</h1>

 <div class="box_posters__list clearfix">

   <?$n=0;

   $k=0;

   foreach($home->homeProducts as $item):

   $k++;

   if($n == 0):

      if($k != 13)echo '<h2>Popular <span>'.$home->getCategoryName($item['object_category']).'</span> Posters and Prints</h2>';

   endif;?>

   <div class="block_names">

        <div class="block_names__image">

          <a href="<?=$item['a_link']?>">

            <img src="<?=$item['img_src']?>"

               title="<?=$item['a_title']?>"

                 alt="<?=$item['a_alt']?>"

               class="img-shadow" />

          </a>

        </div>

        <div class="block_names__title">

          <a href="<?=$item['a_link']?>">

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

<div class="article"><?=$home->seoText?></div>

<br />

</div>