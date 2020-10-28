<?php
//widget



require_once __DIR__ . '/vendor/autoload.php';

class get_commit extends WP_Widget 
{
  
  public function __construct()
  {
      parent::__construct('get_commit', 'GitCommit', array( 'description' => 'Affiche les 10 derniers commit'));
     
  }

  public function widget( $args, $instance ) 
  {
   
   # paramétrages
  $sCompteGithub = "anoop4real";
  $sRepository = "alexa-disheroes";
  $nNombreCommit = 10;
  $response = wp_remote_get( 'https://github.com/KnpLabs/php-github-api' );
  $body     = wp_remote_retrieve_body( $response );
  $http_code = wp_remote_retrieve_response_code( $response );
  $last_modified = wp_remote_retrieve_header( $response, 'last-modified' );
  wp_remote_get( $url, $args );
  $args = array(
    'headers' => array(
        'Authorization' => 'Basic ' . base64_encode( YOUR_USERNAME . ':' . YOUR_PASSWORD )
    )
);

  # Faire la requete à l'API
 $client = new \Github\Client();
  try {
      $aCommits = $client->api('repo')->commits()->all($sCompteGithub, $sRepository, ['path' => ""]);

      // Affichage des resultats
      $nCnt = 0;
      while ( ($nCnt < $nNombreCommit) && isset($aCommits[$nCnt]) ) {

        viewCommit( $aCommits[$nCnt] );

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
    if ( isset( $instance[ 'title' ] ) )
    $title = $instance[ 'title' ];
    else
    $title = __( 'Default Title', 'get_commit_widget_domain' );
    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <?php
  }

function update( $new_instance, $old_instance ) 
  {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    return $instance;
  }

function get_commit_register_widget() 
  {
    register_widget( 'get_commit_widget' );
  }
          

            

}
add_action('widgets_init', function(){register_widget('widgets');} );


