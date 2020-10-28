<?php



add_action('widgets_init','exemple_init');

function exemple_init(){

    register_widget( 'exemple_widget' );
}

class exemple_widget extends WP_widget{

    public function __construct()
    {
      parent::__construct('exemple_widget', 'exemple de Widget', array( 'description' => 'Affiche les 10 derniers commit'));
     
    }
    
    function widget( $args,$instance )
    {
        extract( $args ); 
        echo $before_widget;
        echo $before_title . $instance["titre"] . $after_title;
        include_once(ABSPATH . WPINC . '/rss.php');
        wp_rss($instance["url"]);
    }    

    function update( $new,$old )
    {
        return $new;
    }

    function form( $instance )
    {

        $defaut = array(
            'titre'=> "widget d'exemple",
        );
        $instance = wp_parse_args( $instance, $defaut)
        ?>
        <p> 
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">'Title:' </label>
        <input value="<?php echo $instance['title']; ?>" name="<?php echo $this->get_field_name("title"); ?>" id="<?php echo $this->get_field_id("titre");?>"type="text"/>
        </p>
       
        <p> 
        <label for="<?php echo $this->get_field_id( 'url' ); ?>">'url:' </label>
        <input value="<?php echo $instance['url']; ?>" name="<?php echo $this->get_field_name("url"); ?>" id="<?php echo  $this->get_field_id("url"); ?>"type="text"/>
        </p>

        <p>
            <label for="rss-url-3">saisissezl'url ici:</label>
            <input id="rss-url-3" class="widefat" name="widget-rss[url]"
            type="text value="">
        </p>
        <label for="rss-items-3">Combien d'entrées souhaitez-vous afficher?</label>
        <p>
        <select id="rss-items-3" name="widget-rss[3][items]
            <option value="1">1</option>
            <option value="1">2</option>
            <option value="1">3</option>
            <option value="1">4</option>
            <option value="1">5</option>
            <option value="1">6</option>
            <option value="1">7</option>
            <option value="1">8</option>
        </select>
        </p>
        <p>
        <input id="rss-show-summary-3" name="widget-rss[3][show-summary]"
        type="checkbox" value="1">
            <label for="rss-show-summary-3">Afficher le contenu de l'élément</label>
            <br>
            <input id="rss-show-author-3" name="widget-rss[3][show_author]"
            type="checkbox" value="1">
        </p>
     <?php 
       
    }
}

