<?php
// Single product page
get_header();
$product_content = get_the_content();
?>

<div id="content" class="site-content">
	<div id="primary" class="content-area wrapper product_left_menu tocActive">
		<aside>
            <script src="https://api.sedoo.fr/aeris-cdn-rest/jsrepo/v1_0/download/sandbox/release/sedoocampaigns/0.1.0"></script>
            <?php 
                $product_nav_menu_id = get_field('main-products-campain-menu', 'option');
            ?>
            <span id="button_fold_menu" class="dashicons dashicons-arrow-left-alt2"></span>
            <campaign-product-tree menu_api_url="<?php echo home_url(); ?>/wp-json/menus/v1/menus/<?php echo $product_nav_menu_id; ?>"></campaign-product-tree>
		</aside>
		<main id="main" class="site-main">
			<div class="wrapper-content">
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>> 
                    <section class="sedoo-campaign-view-product">
                        <?php 
                            $product = get_field('id'); // get product id and name
                            $breadcrumb = get_field('name'); // get product id and name
                            $type_produit = get_field('type');

                            $campaign = get_field('nom_de_la_campagne', 'option');   

                            // get service url and package url from option page
                            while( have_rows('field_6054902922fe1', 'option') ) : the_row();
                            if(get_sub_field('type_de_produit') == $type_produit) {
                                $service_url = get_sub_field('url_du_service');
                                $package_url = get_sub_field('url_du_package');
                            }
                            endwhile;

                            // selection du type de viewer
                            $type_viewer = '';
                            switch ($type_produit) {
                                case 'calendarbasedproduct':
                                    break;
                                case 'filetree':
                                    $type_viewer= 'viewer="tree"';
                                    break;
                                case 'wmsproduct':
                                    $type_viewer= 'viewer="wmts"';
                                    break;
                                case 'iframeproduct':
                                    $type_viewer= 'viewer="iframe"';
                                    break;
                            }
                        ?>
                        <h1> <?php echo get_the_title(); ?> </h1>
                        <?php
                        if ( ! post_password_required() ) {
                        ?>
                        <div class="tabs">
                            <?php 
                                if ((get_field('sedoo_campaign_product_label_informations', get_the_ID())) ||  !empty($product_content) ){
                            ?>
                            <nav role="tablist" aria-label="Description and informations">

                                <button role="tab" aria-selected="true" aria-controls="visu" id="tab-1" tabindex="0">
                                    Visualisation
                                </button>
                                <button role="tab" aria-selected="false" aria-controls="sedoo_campaign_product_description" id="tab-2" tabindex="-1">
                                    <?php 
                                    if(get_field('sedoo_campaign_product_description_label', get_the_ID())) {
                                        echo get_field('sedoo_campaign_product_description_label', get_the_ID());
                                    } else {
                                        echo 'Description';
                                    }
                                    ?>
                                </button>
                                <button role="tab" aria-selected="false" aria-controls="sedoo_campaign_product_informations" id="tab-3" tabindex="-1">
                                    <?php 
                                    if(get_field('sedoo_campaign_product_label_informations', get_the_ID())) {
                                        echo get_field('sedoo_campaign_product_label_informations', get_the_ID());
                                    } else {
                                        echo 'Details';
                                    }
                                    ?>
                                </button>
                                
                            </nav>
                            <?php 
                                }
                            ?>
                            <section id="visu" role="tabpanel" tabindex="0" aria-labelledby="tab-1">
                                <campaign-product <?php echo $type_viewer; ?> service="<?php echo $service_url; ?>" campaign="<?php echo $campaign; ?>" product="<?php echo $product; ?>" breadcrumb='<?php echo $breadcrumb; ?>' vce-ready="">
                                </campaign-product>
                            </section>
                            <section id="sedoo_campaign_product_description" role="tabpanel" tabindex="0" aria-labelledby="tab-2" hidden>
                                <?php echo $product_content; ?>
                            </section>
                            <section id="sedoo_campaign_product_informations" role="tabpanel" tabindex="0" aria-labelledby="tab-2" hidden>
                                <?php echo get_field('sedoo_campaign_product_information', get_the_ID()); ?>
                            </section>
                            
                        </div>
                        <?php
                        // if ( ! post_password_required() ) {
                        ?>
                        
                        <?php
                        } else {
                            the_content();
                        }
                        ?>
                    </section> 
				</article>
			</div>
		</main>
		<!-- #main -->
	</div>
	<!-- #primary -->
</div>

<script>
jQuery('#button_fold_menu').click(function(){
    jQuery('#primary>aside').toggleClass('treemenufolded');
});


/**
 *  TABS ON FRONT
 */
/*
 *   This content is licensed according to the W3C Software License at
 *   https://www.w3.org/Consortium/Legal/2015/copyright-software-and-document
 */
window.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('[role="tab"]');
    const tabList = document.querySelector('[role="tablist"]');
    console.log("YOOO");
    // Add a click event handler to each tab
    tabs.forEach(tab => {
      tab.addEventListener('click', changeTabs);
    });
  
    // Enable arrow navigation between tabs in the tab list
    let tabFocus = 0;
  
    tabList.addEventListener('keydown', e => {
      // Move right
      if (e.keyCode === 39 || e.keyCode === 37) {
        tabs[tabFocus].setAttribute('tabindex', -1);
        if (e.keyCode === 39) {
          tabFocus++;
          // If we're at the end, go to the start
          if (tabFocus >= tabs.length) {
            tabFocus = 0;
          }
          // Move left
        } else if (e.keyCode === 37) {
          tabFocus--;
          // If we're at the start, move to the end
          if (tabFocus < 0) {
            tabFocus = tabs.length - 1;
          }
        }
  
        tabs[tabFocus].setAttribute('tabindex', 0);
        tabs[tabFocus].focus();
      }
    });
  });
  
  function changeTabs(e) {
    const target = e.target;
    const parent = target.parentNode;
    const grandparent = parent.parentNode;
  
    // Remove all current selected tabs
    parent
      .querySelectorAll('[aria-selected="true"]')
      .forEach(t => t.setAttribute('aria-selected', false));
  
    // Set this tab as selected
    target.setAttribute('aria-selected', true);
  
    // Hide all tab panels
    grandparent
      .querySelectorAll('[role="tabpanel"]')
      .forEach(p => p.setAttribute('hidden', true));
  
    // Show the selected panel
    grandparent.parentNode
      .querySelector(`#${target.getAttribute('aria-controls')}`)
      .removeAttribute('hidden');
  }

  
</script>
<?php 
get_footer();
?>
