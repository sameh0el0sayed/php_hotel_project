<?php
/* ==============================================
 * CSS AND JAVASCRIPT USED IN THIS MODEL
 * ==============================================
 */
$stylesheets[] = array('file' => DOCBASE.'js/plugins/royalslider/royalslider.css', 'media' => 'all');
$stylesheets[] = array('file' => DOCBASE.'js/plugins/royalslider/skins/minimal-white/rs-minimal-white.css', 'media' => 'all');
$stylesheets[] = array('file' => DOCBASE."templates/default/sr_slider_assets/css/owl.carousel.min.css", 'media' => 'all');
$stylesheets[] = array('file' => DOCBASE."templates/default/sr_slider_assets/css/owl.theme.default.css", 'media' => 'all');


$javascripts[] = DOCBASE.'js/plugins/royalslider/jquery.royalslider.min.js';
$javascripts[] = DOCBASE.'js/plugins/live-search/jquery.liveSearch.js';
 
 

require(getFromTemplate('common/header.php', false));

$slide_id = 0;
$result_slide_file = $db->prepare('SELECT * FROM pm_slide_file WHERE id_item = :slide_id AND checked = 1 AND lang = '.DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
$result_slide_file->bindParam('slide_id', $slide_id);

$result_slide = $db->query('SELECT * FROM pm_slide WHERE id_page = '.$page_id.' AND checked = 1 AND lang = '.LANG_ID.' ORDER BY rank', PDO::FETCH_ASSOC);
if($result_slide !== false){
	$nb_slides = $db->last_row_count();
	if($nb_slides > 0){ ?>
        
        <div id="search-home-wrapper">
            <div id="search-home" class="container">
                <?php include(getFromTemplate('common/search.php', false)); ?>
            </div>
        </div>
	
		<section id="sliderContainer">
            
			<div id="mainSlider" class="royalSlider rsMinW sliderContainer fullWidth clearfix fullSized">
                <?php
                foreach($result_slide as $i => $row){
                    $slide_id = $row['id'];
                    $slide_legend = $row['legend'];
                    $url_video = $row['url'];
                    $id_page = $row['id_page'];
                    
                    $result_slide_file->execute();
                    
                    if($result_slide_file !== false && $db->last_row_count() == 1){
                        $row = $result_slide_file->fetch();
                        
                        $file_id = $row['id'];
                        $filename = $row['file'];
                        $label = $row['label'];
                        
                        $realpath = SYSBASE.'medias/slide/big/'.$file_id.'/'.$filename;
                        $thumbpath = DOCBASE.'medias/slide/small/'.$file_id.'/'.$filename;
                        $zoompath = DOCBASE.'medias/slide/big/'.$file_id.'/'.$filename;
                            
                        if(is_file($realpath)){ ?>
                        
                            <div class="rsContent">
                                <img class="rsImg" src="<?php echo $zoompath; ?>" alt=""<?php if($url_video != '') echo ' data-rsVideo="'.$url_video.'"'; ?>>
                                <?php
                                if($slide_legend != ''){ ?>
                                    <div class="infoBlock" data-fade-effect="" data-move-offset="10" data-move-effect="bottom" data-speed="200">
                                        <?php echo $slide_legend; ?>
                                    </div>
                                    <?php
                                } ?>
                            </div>
                            <?php
                        }
                    }
                } ?>
            </div>
		</section>
		<?php
	}
} ?>
<section id="content" class="pt20 pb30">
    <div class="container">
        
        <?php displayWidgets('before_content', $page_id); ?>
        
        <div class="row">
            <div class="col-md-12 text-center mb30">
                <h1 itemprop="name">
                    <?php
                    echo $page['title'];
                    if($page['subtitle'] != ''){ ?>
                        <br><small><?php echo $page['subtitle']; ?></small>
                        <?php
                    } ?>
                </h1>
                <?php echo $page['text']; ?>
            </div>
        </div>
        
		<?php displayWidgets('after_content', $page_id); ?>
        
        <div class="row mb10">
                                                        <h2 class="text-center mt10 mb15"><?php echo $texts['TOP_HOTELS']; ?></h2>

            <?php
            $result_hotel = $db->query('SELECT * FROM pm_hotel WHERE lang = '.LANG_ID.' AND checked = 1 AND home = 1 ORDER BY rank');
            if($result_hotel !== false){
                $nb_hotels = $db->last_row_count();
                
                $hotel_id = 0;
                
                $result_hotel_file = $db->prepare('SELECT * FROM pm_hotel_file WHERE id_item = :hotel_id AND checked = 1 AND lang = '.DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
                $result_hotel_file->bindParam(':hotel_id',$hotel_id);
                
                $result_rate = $db->prepare('SELECT MIN(price) as min_price FROM pm_rate WHERE id_hotel = :hotel_id');
                $result_rate->bindParam(':hotel_id', $hotel_id);
                
                foreach($result_hotel as $i => $row){
                    $hotel_id = $row['id'];
                    $hotel_title = $row['title'];
                    $hotel_subtitle = $row['subtitle'];
                    
                    $hotel_alias = DOCBASE.$pages[9]['alias'].'/'.text_format($row['alias']);
                    
                    $min_price = 0;
                    if($result_rate->execute() !== false && $db->last_row_count() > 0){
                        $row = $result_rate->fetch();
                        $price = $row['min_price'];
                        if($price > 0) $min_price = $price;
                    } ?>
                    
                    <article class="col-sm-4 mb20" itemscope itemtype="http://schema.org/LodgingBusiness">
                        <a itemprop="url" href="<?php echo $hotel_alias; ?>" class="moreLink">
                            <?php
                            if($result_hotel_file->execute() !== false && $db->last_row_count() == 1){
                                $row = $result_hotel_file->fetch(PDO::FETCH_ASSOC);
                                
                                $file_id = $row['id'];
                                $filename = $row['file'];
                                $label = $row['label'];
                                
                                $realpath = SYSBASE.'medias/hotel/small/'.$file_id.'/'.$filename;
                                $thumbpath = DOCBASE.'medias/hotel/small/'.$file_id.'/'.$filename;
                                $zoompath = DOCBASE.'medias/hotel/big/'.$file_id.'/'.$filename;
                                
                                if(is_file($realpath)){
                                    $s = getimagesize($realpath); ?>
                                    <figure class="more-link">
                                        <div class="img-container lazyload md">
                                            <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" width="<?php echo $s[0]; ?>" height="<?php echo $s[1]; ?>">
                                        </div>
                                        <div class="more-content">
                                            <h3 itemprop="name"><?php echo $hotel_title; ?></h3>
                                            <?php
                                            if($min_price > 0){ ?>
                                                <div class="more-descr">
                                                    <div class="price">
                                                        <?php echo $texts['FROM_PRICE']; ?>
                                                        <span itemprop="priceRange">
                                                            <?php echo formatPrice($min_price*CURRENCY_RATE); ?>
                                                        </span>
                                                    </div>
                                                    <small><?php echo $texts['PRICE'].' / '.$texts['NIGHT']; ?></small>
                                                </div>
                                                <?php
                                            } ?>
                                        </div>
                                        <div class="more-action">
                                            <div class="more-icon">
                                                <i class="fa fa-link"></i>
                                            </div>
                                        </div>
                                    </figure>
                                    <?php
                                }
                            } ?>
                        </a> 
                    </article>
                    <?php
                }
            } ?>
        </div>



        <?php    
            $result_article = $db->query('SELECT *
                                    FROM pm_article
                                    WHERE (id_page = '.$page_id.' )
                                        AND checked = 1
                                        AND (publish_date IS NULL || publish_date <= '.time().')
                                        AND (unpublish_date IS NULL || unpublish_date > '.time().')
                                        AND lang = '.LANG_ID.'
                                        AND (show_langs IS NULL || show_langs = \'\' || show_langs REGEXP \'(^|,)'.LANG_ID.'(,|$)\')
                                        AND (hide_langs IS NULL || hide_langs = \'\' || hide_langs NOT REGEXP \'(^|,)'.LANG_ID.'(,|$)\')
                                    ORDER BY rank');
            if($result_article !== false){
                $nb_articles = $db->last_row_count();
                
                if($nb_articles > 0){ ?>

                    
                    <div class="row mb10">
                    <h2 class="text-center mt10 mb15"><?php echo $texts['FIND_ACTIVITIES_AND_TOURS']; ?></h2>

                    <?php
                                    $article_id = 0;
                                    $result_article_file = $db->prepare('SELECT * FROM pm_article_file WHERE id_item = :article_id  AND lang = '.DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
                                    $result_article_file->bindParam(':article_id', $article_id);
                                    foreach($result_article as $i => $row){
                                        $article_id = $row['id'];
                                        $article_title = $row['title'];
                                        $article_alias = $row['alias'];
                                        $char_limit = ($i == 0) ? 1200 : 500;
                                        $article_text = $row['text'];
                                        $article_page = $row['id_page'];
                                            
                                        if(isset($pages[$article_page])){
                                                if($result_article_file->execute() !== false && $db->last_row_count() == 1){
                                                    $row = $result_article_file->fetch(PDO::FETCH_ASSOC);
                                                    
                                                    $file_id = $row['id'];
                                                    $filename = $row['file'];
                                                    $label = $row['label'];
                                                    
                                                    $realpath = SYSBASE.'medias/article/big/'.$file_id.'/'.$filename;
                                                    $thumbpath = DOCBASE.'medias/article/big/'.$file_id.'/'.$filename;
                                                    $zoompath = DOCBASE.'medias/article/big/'.$file_id.'/'.$filename;
                                                    
                                                    if(is_file($realpath)){
                                                        $s = getimagesize($realpath); 
                                                        $article_alias = (empty($article_url)) ? DOCBASE.$pages[$article_page]['alias'].'/'.text_format($article_alias) : $article_url;
                                                        $target = (strpos($article_alias, 'http') !== false) ? '_blank' : '_self';
                                                        if(strpos($article_alias, getUrl(true)) !== false) $target = '_self';  
                                                        
                                                        ?>
                                                        
                                                            <article class="col-sm-4 mb20" itemscope="" itemtype="http://schema.org/LodgingBusiness">
                                                                <a href="#" data-toggle="modal" data-target="#model_<?php echo $article_id ?>" class="">
                                                                    <figure class="more-link">
                                                                        <div class="img-container lazyload md" style="overflow: hidden; position: relative;">
                                                                                <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" width="<?php echo $s[0]; ?>" height="<?php echo $s[1]; ?>">
                                                                        </div>
                                                                        <div class="more-content">
                                                                            <h3 itemprop="name"><?php echo $article_title ?> </h3>
                                                                                                                                            
                                                                        </div>
                                                                                    
                                                                    </figure>
                                                                </a> 
                                                            </article>
                                    
                                                            <!-- Modal -->
                                                            <div class="modal fade" id="model_<?php echo $article_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                    <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $article_title ?> </h5>
                                                                        
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                            
                                                                                <div class="img-container lazyload md" style="overflow: hidden; position: relative;">
                                                                                            <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" width="<?php echo $s[0]; ?>" height="<?php echo $s[1]; ?>">
                                                                                </div>
                                                                            </div>
                                                                        </div>  
                                                                        
                                                                        <div class="row">
                                                                            

                                                                            <div class="col-sm-12">
                                                                                <br>
                                                                            
                                                                                <div class="pre-scrollable">
                                                                                <?php echo $article_text; ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>                                                                    
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php
                                                    }
                                                } 
                                        
                                        }
                                        
                                    }
                                    ?>







                    </div>
    

                

                    <?php
                }
            } 
        
        ?>    



        
            


    </div>



  

    <?php
    // $javascripts[] = DOCBASE.'templates/default/sr_slider_assets/js/owl128.carousel.min.js';

    $activity_id = 0;
    $result_activity = $db->query('SELECT * FROM pm_activity WHERE lang = '.LANG_ID.' AND checked = 1 AND home = 1 ORDER BY rank');
    if($result_activity !== false){
        $nb_activities = $db->last_row_count();
        if($nb_activities > 0){ ?>
            <div class="hotBox mb30 mt5">
                <div class="container-fluid">
                    <div class="row">
                        <h2 class="text-center mt10 mb15"><?php echo $texts['FIND_ACTIVITIES_AND_TOURS']; ?></h2>
                        <?php
                        $activity_id = 0;
                        $result_activity_file = $db->prepare('SELECT * FROM pm_activity_file WHERE id_item = :activity_id AND checked = 1 AND lang = '.DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
                        $result_activity_file->bindParam(':activity_id',$activity_id);
                        foreach($result_activity as $i => $row){
                            $activity_id = $row['id'];
                            $activity_title = $row['title'];
                            $activity_alias = $row['title'];
                            $activity_subtitle = $row['subtitle'];
                            $min_price = $row['price'];
                            
                            $activity_alias = DOCBASE.$sys_pages['activities']['alias'].'/'.text_format($row['alias']); ?>
                            
                            <article class="col-sm-3 mb20" itemscope itemtype="http://schema.org/LodgingBusiness">
                                <a itemprop="url" href="<?php echo $activity_alias; ?>" class="moreLink">
                                    <?php
                                    if($result_activity_file->execute() !== false && $db->last_row_count() > 0){
                                        $row = $result_activity_file->fetch(PDO::FETCH_ASSOC);
                                        
                                        $file_id = $row['id'];
                                        $filename = $row['file'];
                                        $label = $row['label'];
                                        
                                        $realpath = SYSBASE.'medias/activity/small/'.$file_id.'/'.$filename;
                                        $thumbpath = DOCBASE.'medias/activity/small/'.$file_id.'/'.$filename;
                                        $zoompath = DOCBASE.'medias/activity/big/'.$file_id.'/'.$filename;
                                        
                                        if(is_file($realpath)){
                                            $s = getimagesize($realpath); ?>
                                            <figure class="more-link">
                                                <div class="img-container lazyload md">
                                                    <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" width="<?php echo $s[0]; ?>" height="<?php echo $s[1]; ?>">
                                                </div>
                                                <div class="more-content">
                                                    <h3 itemprop="name"><?php echo $activity_title; ?></h3>
                                                </div>
                                                <div class="more-action">
                                                    <div class="more-icon">
                                                        <i class="fa fa-link"></i>
                                                    </div>
                                                </div>
                                            </figure>
                                            <?php
                                        }
                                    } ?>
                                </a> 
                            </article>
                            <?php
                        } ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    
    $result_destination = $db->query('SELECT * FROM pm_destination WHERE lang = '.LANG_ID.' AND checked = 1 AND home = 1 ORDER BY rank');
    if($result_destination !== false){
        $nb_destinations = $db->last_row_count();
        
        if($nb_destinations > 0){ ?>
                    
            <div class="container hotBox mb30 mt5">
                <div class="row mb10">
                    <h2 class="text-center mt5 mb10"><?php echo $texts['TOP_DESTINATIONS']; ?></h2>
                    <?php
                    $destination_id = 0;
                    
                    $result_destination_file = $db->prepare('SELECT * FROM pm_destination_file WHERE id_item = :destination_id AND checked = 1 AND lang = '.DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
                    $result_destination_file->bindParam(':destination_id',$destination_id);
                    
                    $result_rate = $db->prepare('
                        SELECT MIN(ra.price) as min_price
                        FROM pm_rate as ra, pm_hotel as h, pm_destination as d
                        WHERE id_hotel = h.id
                            AND id_destination = d.id
                            AND id_destination = :destination_id');
                    $result_rate->bindParam(':destination_id', $destination_id);
                    
                    foreach($result_destination as $i => $row){
                        $destination_id = $row['id'];
                        $destination_name = $row['name'];
                        $destination_subtitle = $row['subtitle'];
                        
                        $destination_alias = DOCBASE.$sys_pages['booking']['alias'].'/'.text_format($row['alias']);
                        
                        $min_price = 0;
                        if($result_rate->execute() !== false && $db->last_row_count() > 0){
                            $row = $result_rate->fetch();
                            $price = $row['min_price'];
                            if($price > 0) $min_price = $price;
                        } ?>
                        
                        <article class="col-sm-4 mb20" itemscope itemtype="http://schema.org/LodgingBusiness">
                            <a itemprop="url" href="<?php echo $destination_alias; ?>" class="moreLink">
                                <?php
                                if($result_destination_file->execute() !== false && $db->last_row_count() == 1){
                                    $row = $result_destination_file->fetch(PDO::FETCH_ASSOC);
                                    
                                    $file_id = $row['id'];
                                    $filename = $row['file'];
                                    $label = $row['label'];
                                    
                                    $realpath = SYSBASE.'medias/destination/small/'.$file_id.'/'.$filename;
                                    $thumbpath = DOCBASE.'medias/destination/small/'.$file_id.'/'.$filename;
                                    $zoompath = DOCBASE.'medias/destination/big/'.$file_id.'/'.$filename;
                                    
                                    if(is_file($realpath)){
                                        $s = getimagesize($realpath); ?>
                                        <figure class="more-link">
                                            <div class="img-container lazyload md">
                                                <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" width="<?php echo $s[0]; ?>" height="<?php echo $s[1]; ?>">
                                            </div>
                                            <div class="more-content">
                                                <h3 itemprop="name"><?php echo $destination_name; ?></h3>
                                                <?php
                                                if($min_price > 0){ ?>
                                                    <div class="more-descr">
                                                        <div class="price">
                                                            <?php echo $texts['FROM_PRICE']; ?>
                                                            <span itemprop="priceRange">
                                                                <?php echo formatPrice($min_price*CURRENCY_RATE); ?>
                                                            </span>
                                                        </div>
                                                        <small><?php echo $texts['PRICE'].' / '.$texts['NIGHT']; ?></small>
                                                    </div>
                                                    <?php
                                                } ?>
                                            </div>
                                            <div class="more-action">
                                                <div class="more-icon">
                                                    <i class="fa fa-link"></i>
                                                </div>
                                            </div>
                                        </figure>
                                        <?php
                                    }
                                } ?>
                            </a> 
                        </article>
                        <?php
                    } ?>
                </div>
            </div>
            <?php
        }
    }
    
    $result_article = $db->query('SELECT *
                                FROM pm_article
                                WHERE ( home = 1)
                                    AND checked = 1
                                    AND (publish_date IS NULL || publish_date <= '.time().')
                                    AND (unpublish_date IS NULL || unpublish_date > '.time().')
                                    AND lang = '.LANG_ID.'
                                    AND (show_langs IS NULL || show_langs = \'\' || show_langs REGEXP \'(^|,)'.LANG_ID.'(,|$)\')
                                    AND (hide_langs IS NULL || hide_langs = \'\' || hide_langs NOT REGEXP \'(^|,)'.LANG_ID.'(,|$)\')
                                ORDER BY rank');
    if($result_article !== false){
        $nb_articles = $db->last_row_count();
        
        if($nb_articles > 0){ ?>
            <div class="container mt10">
                <div class="row">
                                            <h2 class="text-center mt10 mb15"><?php echo $texts['HOT_DEALS']; ?></h2>

                        <div class="owl-carousel owl-theme">
                            

                                <?php
                                  $article_id = 0;
                                  $result_article_file = $db->prepare('SELECT * FROM pm_article_file WHERE id_item = :article_id AND checked = 1 AND lang = '.DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
                                  $result_article_file->bindParam(':article_id', $article_id);
                                  foreach($result_article as $i => $row){
                                      $article_id = $row['id'];
                                      $article_title = $row['title'];
                                      $article_alias = $row['alias'];
                                      $char_limit = ($i == 0) ? 1200 : 500;
                                      $article_text = strtrunc(strip_tags($row['text'], '<p><br>'), $char_limit, true, '');
                                      $article_page = $row['id_page'];
                                        
                                      if(isset($pages[$article_page])){
                                            if($result_article_file->execute() !== false && $db->last_row_count() == 1){
                                                $row = $result_article_file->fetch(PDO::FETCH_ASSOC);
                                                
                                                $file_id = $row['id'];
                                                $filename = $row['file'];
                                                $label = $row['label'];
                                                
                                                $realpath = SYSBASE.'medias/article/big/'.$file_id.'/'.$filename;
                                                $thumbpath = DOCBASE.'medias/article/big/'.$file_id.'/'.$filename;
                                                $zoompath = DOCBASE.'medias/article/big/'.$file_id.'/'.$filename;
                                                
                                                if(is_file($realpath)){
                                                    $s = getimagesize($realpath); 
                                                    $article_alias = (empty($article_url)) ? DOCBASE.$pages[$article_page]['alias'].'/'.text_format($article_alias) : $article_url;
                                                    $target = (strpos($article_alias, 'http') !== false) ? '_blank' : '_self';
                                                    if(strpos($article_alias, getUrl(true)) !== false) $target = '_self';  
                                                    
                                                    ?>
                                                                <div>
                                                              
                                                                    <article id="article-<?php echo $article_id; ?>"  class="mb20 " itemscope="" itemtype="http://schema.org/Article">
                                                                        <div class="row">
                                                                            <a itemprop="url" href="<?php echo $article_alias; ?>"  target="<?php echo $target; ?>" class="moreLink">
                                                                                <div class="col-sm-6 mb20">
                                                                                    <figure class="more-link">
                                                                                        <div class="img-container lazyload xl">
                                                                                           <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo"  >
                                                                                        </div>
                                                                                        <div class="more-action">
                                                                                            <div class="more-icon">
                                                                                                <svg class="svg-inline--fa fa-link fa-w-16" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="link" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M326.612 185.391c59.747 59.809 58.927 155.698.36 214.59-.11.12-.24.25-.36.37l-67.2 67.2c-59.27 59.27-155.699 59.262-214.96 0-59.27-59.26-59.27-155.7 0-214.96l37.106-37.106c9.84-9.84 26.786-3.3 27.294 10.606.648 17.722 3.826 35.527 9.69 52.721 1.986 5.822.567 12.262-3.783 16.612l-13.087 13.087c-28.026 28.026-28.905 73.66-1.155 101.96 28.024 28.579 74.086 28.749 102.325.51l67.2-67.19c28.191-28.191 28.073-73.757 0-101.83-3.701-3.694-7.429-6.564-10.341-8.569a16.037 16.037 0 0 1-6.947-12.606c-.396-10.567 3.348-21.456 11.698-29.806l21.054-21.055c5.521-5.521 14.182-6.199 20.584-1.731a152.482 152.482 0 0 1 20.522 17.197zM467.547 44.449c-59.261-59.262-155.69-59.27-214.96 0l-67.2 67.2c-.12.12-.25.25-.36.37-58.566 58.892-59.387 154.781.36 214.59a152.454 152.454 0 0 0 20.521 17.196c6.402 4.468 15.064 3.789 20.584-1.731l21.054-21.055c8.35-8.35 12.094-19.239 11.698-29.806a16.037 16.037 0 0 0-6.947-12.606c-2.912-2.005-6.64-4.875-10.341-8.569-28.073-28.073-28.191-73.639 0-101.83l67.2-67.19c28.239-28.239 74.3-28.069 102.325.51 27.75 28.3 26.872 73.934-1.155 101.96l-13.087 13.087c-4.35 4.35-5.769 10.79-3.783 16.612 5.864 17.194 9.042 34.999 9.69 52.721.509 13.906 17.454 20.446 27.294 10.606l37.106-37.106c59.271-59.259 59.271-155.699.001-214.959z"></path></svg><!-- <i class="fa fa-link"></i> -->
                                                                                            </div>
                                                                                        </div>
                                                                                    </figure>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <div class="text-overflow">
                                                                                    <h3 itemprop="name"><?php echo $article_title; ?></h3>
                                                                                        <?php echo $article_text; ?>
                                                                                        <div class="more-btn">
                                                                                            <span class="btn btn-primary"><?php echo $texts['READMORE']; ?></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                    </article>
                                                                </div>
                                                    <?php
                                                }
                                            } 
                                      
                                      }
                                      
                                  }
                                ?>

                    
                                                                             
                        </div>
                </div>
              

                
            </div>

           

            <?php
        }
    } ?>


        <script>
            $(document).ready(function(){
            

                ;(function($){
                    $('.owl-carousel').owlCarousel({
                        
                        // lazyLoad:true,
                        // autoHeight:true,
                        items: 2,
                        loop: false,
                        nav: true,
                        margin:10
                    });

                })(jQuery);

            });
        </script>

    <?php displayWidgets('full_after_content', $page_id); ?>
</section>
