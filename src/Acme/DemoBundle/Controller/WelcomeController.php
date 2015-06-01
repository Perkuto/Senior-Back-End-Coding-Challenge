<?php
/**
 * WelcomeController
 *
 * @category   PHP5
 * @package    Acme
 * @subpackage Controller
 * @author     Tairik Jean da Costa <tairik@hotmail.com>
 * @license    http://www.tairik.com/license/ BSD Licence
 * @link       https://tairik@bitbucket.org/tairik/simplerest.git
 */
namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;/**
 * This class catches the controller and calls the appropriate action requested.
 * It manages everything related to the home page, etc.
 *
 * @category   PHP5
 * @package    Acme
 * @subpackage Controller
 * @author     Tairik Jean da Costa <tairik@hotmail.com>
 * @license    http://www.tairik.com/license/ BSD Licence
 * @link       https://tairik@bitbucket.org/tairik/simplerest.git
 */

class WelcomeController extends Controller
{
    public function indexAction()
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in DemoController.
         *
         */

        return $this->render('AcmeDemoBundle:Welcome:index.html.twig');
    }

    /**
     * importAction Keep commands to import CSV files into DB
     *
     * @return html
     */
    public function importAction()
    {
		echo "LOAD DATA LOCAL INFILE '/var/www/simplerest/web/users.csv' INTO TABLE user FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES;";

		echo '<br />';

		echo "LOAD DATA LOCAL INFILE '/var/www/simplerest/web/cities.csv' INTO TABLE city FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES;";
    }

    /**
     * notesAction Notes about the project
     *
     * @return view
     */
    public function notesAction()
    {
        return $this->render('AcmeDemoBundle:Welcome:notes.html.twig');
    }
}