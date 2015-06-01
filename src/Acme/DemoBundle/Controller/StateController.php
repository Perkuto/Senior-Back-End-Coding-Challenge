<?php
/**
 * StateController
 *
 * @category   PHP5
 * @package    Acme
 * @subpackage Controller
 * @author     Tairik Jean da Costa <tairik@hotmail.com>
 * @license    http://www.tairik.com/license/ BSD Licence
 * @link       https://tairik@bitbucket.org/tairik/simplerest.git
 */
namespace Acme\DemoBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query\ResultSetMapping;
/**
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

class StateController extends Controller
{

  	/**
     * getStateCitiesAction get all cities
     *
     * @param string $state    state
     *
     * @return json
     */
    public function getStateCitiesAction($state)
    {
        $objEntityManager   = $this->getDoctrine()->getManager();
        $objCityRepository  = $objEntityManager->getRepository('AcmeDemoBundle:City');
        $aryOjbCity         = $objCityRepository->findBy(array('state'=>$state));
        return $aryOjbCity;
  	}

  	//v1/states/{state}/cities/{city}?radius=100`
  	//v1/states/{state}/cities/{city}/city.{_format}
    /**
     * getStateCitiesCityAction Cities radius
     *
     * @param Request $request ojb Request
     * @param string $state    state
     * @param string $city     city
     *
     * @return json
     */
  	public function getStateCitiesCityAction(Request $objRequest, $state, $city)
    {
        $objEntityManager  = $this->getDoctrine()->getManager();
        $objCityRepository = $objEntityManager->getRepository('AcmeDemoBundle:City');
        $ojbCity           = $objCityRepository->findOneBy(array('name'=>$city));
        $response          = array();

        if (is_object($ojbCity)) {
            $latitude  = $ojbCity->getLatitude();
            $longitude = $ojbCity->getLongitude();

            $radius  = $objRequest->get('radius');
            $request = $this->getRequest();
            $page    = $request->get('page');
            if ($page === 'all' || $page === null) {
                $limit   = false;
                $offset  = false;
            } else {
                $limit   = 10;
                $offset  = ($page - 1)  * $limit;
            }

            $aryOjbCity = $objCityRepository->getAllCitiesByRadius($latitude, $longitude, $radius, $offset, $limit);

            $response = $aryOjbCity;
        } else {

            $response['status']  = 'nok';
            $response['message'] = 'City not found';
        }

        return $response;
    }
}