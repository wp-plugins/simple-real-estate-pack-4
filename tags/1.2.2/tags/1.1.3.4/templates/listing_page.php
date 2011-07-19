<?php if (function_exists('get_listing_status')) { ?>
	<?php getandsetup_listingdata(); ?>
<div id="srp_listing_details">
    <div class="clearfix">
    <div class="listing-slideshow span-10">
            <div class="box">
            <?php 
            if(function_exists('nggShowSlideshow')){
                echo nggShowSlideshow(get_listing_galleryid(), $width='356', $height='267');
            }
            ?>
            </div>
    </div>
    <div class="page-propdata-box span-5 last">
            <div class="box">
            <h2><span>Property Details</span></h2>
            <?php
                    $details = array();
            ?>

            <div class="page-blurb"><?php the_listing_blurb(); ?></div>
            <?php
                    if ($bedrooms = get_listing_bedrooms())
                            $details[] = "$bedrooms Bedrooms";
                    if ($bathrooms = get_listing_bathrooms()) {
                            $bath = "$bathrooms Full ";
                    if ($halfbaths = get_listing_halfbaths())
                            $bath .= "&amp; $halfbaths Half ";
                            $bath .= " Baths";
                  }
                            $details[] = $bath;
                    if ($garage = get_listing_garage())
                            $details[] = "$garage Garage Spaces";
                    if ($acsf = get_listing_acsf())
                            $details[] = "$acsf Sq/Ft Under Air";
                    if ($totsf = get_listing_totsf())
                            $line2 .= "$totsf Sq/Ft Total";
                            $acres = get_listing_acres();
                    if ($acres > 0)
                            $details[] = "$acres Acres";
                    if (get_listing_haspool())
                            $details[] = "Private Pool";
                    if (get_listing_haswater())
                            $details[] = "Waterfront";
                    if (get_listing_hasgolf())
                            $details[] = "On Golf Course";
                    if (!empty($details)) { ?>
          <div class='propdata'>
                    <ul>
            <?php
                    $i = 0;
                    foreach($details as $line){
                            $i++;
                            if($i&1){ $class = 'class="odd"'; }else{$class = 'class="even"';}
                            print "<li $class>$line</li>";

                    }
            ?>
                    </ul>
                    <h4><?php the_listing_status(); ?>
                    <?php if (get_listing_hasclosed()) { ?>
                    <?php the_listing_saledate(); ?> for <?php the_listing_saleprice(); ?> - last offered<?php } else { ?>- Offered<?php } ?>  at <?php the_listing_listprice(); ?></h4>
          </div>
            </div>
    </div>
    </div>
            <?php } ?>
    <?php the_listing_description_content(); ?>

    <?php } else { ?>
    <?php //the_content(); // plugin disabled, just spit out the normal content ?>
    <?php } ?>

    <?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>


    <?php if (function_exists('get_listing_status')) { ?>
    <div id="listing-container">

        <?php
            /* Begin SRP Template Code */
            global $srp_property_values;
            $srp_property_values = array(
                'lat' => get_listing_latitude(),
                'lng' => get_listing_longitude(),
                'address' => get_listing_address(),
                'city' => get_listing_city(),
                'state' => get_listing_state(),
                'zip_code' => get_listing_postcode(),
                'listing_price' => get_listing_listprice(),
                'bedrooms'  => get_listing_bedrooms(),
                'bathrooms' => get_listing_bathrooms(),
                'html' => '<div style="text-align: center;">' . get_listing_thumbnail() . '</div><div style="text-align: center;font-size: 14px;line-height: normal;"><strong>' . get_listing_listprice() . '</strong></div><p style="text-align: center;font-size: 12px;line-height: normal;">' . get_listing_address() . ',<br />' . get_listing_city() . ' ' . get_listing_state() . ' ' . get_listing_postcode() . '</p>',

            );

            if(function_exists('srp_profile')){
                srp_profile();
            }else{
                ?>
                <ul id="tabnav">
                <?php the_listing_map_tab(); // recommend this be first ?>
                <?php //the_listing_description_tab(); ?>
                <?php //the_listing_gallery_tab(); ?>
                <?php the_listing_video_tab(); ?>
                <?php the_listing_panorama_tab(); ?>
                <?php the_listing_downloads_tab(); ?>
                <?php the_listing_community_tab(); ?>
                </ul>
                <?php the_listing_map_content(); // recommend this be first ?>
                <?php the_listing_description_content(); ?>
                <?php the_listing_gallery_content(); ?>
                <?php the_listing_video_content(); ?>
                <?php the_listing_panorama_content(); ?>
                <?php the_listing_downloads_content(); ?>
                <?php the_listing_community_content();
            }
                    /* End SRP Template Code */
            ?>

            <?php
            //TODO: Give users an option to embed their own form.
            if(function_exists('insert_form')){
                print '<a name="more_info"></a><h2><span>Request More Information</span></h2>';
                insert_cform('Request More Information');
            }
            ?>
    <?php } ?>
    </div>
</div>