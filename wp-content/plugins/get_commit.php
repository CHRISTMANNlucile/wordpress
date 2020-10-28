<?php
//widget

/*
Plugin Name: Plugin Commit
Plugin URI: http://wordpress.org/plugins/get_commit/
Description: affichage les 10 derniers commits d'un dépot Github 
Version: 1.0
Author: Lucile Christmann
*/

require_once __DIR__ . '/vendor/autoload.php';

class get_commit extends WP_Widget 
{
  
  public function __construct()
  {
      parent::__construct('get_commit', 'GitCommit', array( 'description' => 'Affiche les 10 derniers commit'));
     
  }

  function widget( $args,$instance )
  {
      extract( $args ); 
      echo $before_widget;
      echo $before_title . $instance["titre"] . $after_title;
      include_once(ABSPATH . WPINC . '/rss.php');
      wp_rss($instance["url"]);
  }    

    public function get_github_commits($user, $repo, $count)
  {
   # paramétrages
   $user = "CHRISTMANNlucile";
   $repo = "github_commit";
   $count = 10;

  # Faire la requete à l'API
  $client = new GuzzleHttp\Client();
  $response = $client->get('http://guzzlephp.org');
  $res = $client->get('https://github.com/KnpLabs/php-github-api', ['auth' =>  ['user', 'pass']]);
  
  try {
      $aCommits = $client->api('https://github.com/KnpLabs/php-github-api')->commits()->all($user, $repo, ['path' => ""]);

      // Affichage des resultats
      $nCnt = 0;
      while ( ($nCnt < $count) && isset($aCommits[$nCnt]) ) {

        echo $res->getStatusCode();
        // "200"
        echo $res->getHeader('content-type');
        // 'application/json; charset=utf8'
        echo $res->getBody();
        // {"type":"User"...'
        var_export($res->json());
        // Outputs the JSON decoded data

        $nCnt++;
      }


      foreach ($aCommits as $key => $aCommit) {

      }

    } catch (\RuntimeException $e) {
      echo "Erreur acces API Github";
    }
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

function update( $new_instance, $old_instance ) 
  {
        return $new_instance;
  }

function get_commit_register_widget() 
  {
    register_widget( 'get_commit_widget' );
  }
          

            

}
add_action('widgets_init', function(){register_widget('widgets');} );


