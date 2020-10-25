<?php
//widget

/*
Plugin Name: Plugin get_commit
Plugin URI: http://wordpress.org/plugins/get_commit/
Description: affichage pour le site https://www.wordpress/
Version: 1.0
Author: Lucile Christmann
*/
require_once __DIR__ . '/vendor/autoload.php';

class get_commit extends WP_Widget 
{

  public function __construct()
  {
      parent::__construct('get_commit', 'get_commit', array( 'customize_selective_refresh' => true,));
     
  }

  public function widget( $args, $instance ) 
  {
   # paramétrages
  $sCompteGithub = "CHRISTMANNlucile";
  $sRepository = "wordpress";
  $nNombreCommit = 10;

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

 public function form( $instance ) 
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

  public function update( $new_instance, $old_instance ) 
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
add_action( 'widgets_init', 'get_commit_register_widget' );

