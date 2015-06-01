<?php
/**
 * UserController
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
use FOS\RestBundle\Request\ParamFetcher;
use Acme\DemoBundle\Form\UserType;
use Acme\DemoBundle\Entity\User;
use Acme\DemoBundle\Entity\UserCity;
use Acme\DemoBundle\Entity\UserPhoto;
use FOS\RestBundle\Controller\FOSRestController;
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

class UserController extends FosRestController
{

    const IMAGE_UPDATED      = 'image has been updated';
    const NOK                = 'not ok';
    const OK                 = 'ok';
    const USER_NOT_VALID     = 'user not valid';
    const ERR_UPLOAD_IMAGE   = 'error uploading image';
    const FIELDS_MADATORIES  = 'file and public mandatories fields';
    const PHOTO_INVALID      = 'photo not valid';
    const IMAGE_DELETED      = 'image has been deleted';
    const IMAGE_USER         = 'image and user does not match';
    const IMAGE_NOT_FOUND    = 'Photo Id not found';
    const USER_CITY_EXISTS   = 'User / City already exists';
    const USER_CITY_INSERTED = 'User / City inserted';

    /**
     * getUserAction Index page
     *
     * @return json
     */
    public function getAllUsersAction()
    {
        $request = $this->getRequest();
        $page    = $request->get('page');
        $limit   = 3;
        $offset  = 0;

        $objEntityManager  = $this->getDoctrine()->getManager();
        $objUserRepository = $objEntityManager->getRepository('AcmeDemoBundle:User');
        $aryObjUser        = $objUserRepository->findBy(
                   array(), // $where
                   array(), // $orderBy
                   $limit,  // $limit
                   $offset  // $offset
                   );
        return $aryObjUser;
    }

    /**
     * getForm Build de form
     *
     * @param integer $user
     *
     * @return void
     */
    protected function getForm($user = null)
    {
        return $this->createForm(new UserType(), $user);
    }

  	/**
     * getUserVisitsAction Index page
     *
     * @param integer $user
     *
     * @return json
     */
    public function getUserVisitsAction($user)
    {

        $objEntityManager  = $this->getDoctrine()->getManager();
        $objUserRepository = $objEntityManager->getRepository('AcmeDemoBundle:User');
        $ojbUser           = $objUserRepository->findOneBy(array('id'=>$user));

        if (is_object($ojbUser)) {
            $objUserCityRepository = $objEntityManager->getRepository('AcmeDemoBundle:UserCity');
            $aryOjbUserCity        = $objUserCityRepository->findBy(array('userId'=>$ojbUser->getId()));

            if (is_array($aryOjbUserCity)) {
                return $aryOjbUserCity;
            } else {
                throw $this->createNotFoundException();
            }
        } else {
            throw new \Exception('User not found!');
        }
    }

    /**
     * getUserPhotosAction Index page
     *
     * @param integer $user
     *
     * @return json
     */
    public function getUserPhotosAction($user)
    {

        $objEntityManager  = $this->getDoctrine()->getManager();
        $objUserRepository = $objEntityManager->getRepository('AcmeDemoBundle:User');
        $ojbUser           = $objUserRepository->findOneBy(array('id'=>$user));

        if (is_object($ojbUser)) {
            $objUserPhotoRepository = $objEntityManager->getRepository('AcmeDemoBundle:UserPhoto');
            $aryOjbUserPhoto        = $objUserPhotoRepository->findBy(array('userId'=>$ojbUser->getId()));

            foreach ($aryOjbUserPhoto as $objUserPhoto) {
                $aryParts = explode('/', $objUserPhoto->getPhoto());
                $file     = $aryParts[sizeof($aryParts)-1];
                $url      = $this->container->getParameter('getUserPhotosUrl');
                $objUserPhoto->setPhoto($url . $ojbUser->getId() . '/' . $file);
            }

            if (is_array($aryOjbUserPhoto)) {
                return $aryOjbUserPhoto;
            } else {
                throw $this->createNotFoundException();
            }
        } else {
            throw new \Exception('User not found!');
        }
    }

    /**
     * postUserAddPhotosAction Index page
     *
     * @param Request $request ojb Request
     * @param integer $user    user
     *
     * @return json
     */
    public function postUserAddPhotosAction(Request $request, $user)
    {
        $response  = array();
        $objFile   = $request->files->get('file');
        $public    = $request->get('public');
        if ($objFile != null && $public != null) {

            $extension = $objFile->getClientOriginalExtension();
            $path      = $this->container->getParameter('getUserPhotosPath');
            if ($objFile->getError() === 0) {
                try {

                $objEntityManager  = $this->getDoctrine()->getManager();
                $objUserRepository = $objEntityManager->getRepository('AcmeDemoBundle:User');
                $fileName          = strtolower($objUserRepository->getLastFile($user));
                $ojbUser           = $objUserRepository->findOneBy(array('id'=>$user));

                if (is_object($ojbUser)) {
                    $fileNameFull = $path . $ojbUser->getId() . '/' . $fileName  . '.' . $extension;
                    $objFile->move($path . $user, $fileName . '.'.$extension);

                    $objUserPhoto = new UserPhoto();
                    $objUserPhoto->setUserId($ojbUser->getId());
                    $objUserPhoto->setPhoto($fileNameFull);
                    $objUserPhoto->setPublic($public);
                    $objEntityManager->persist($objUserPhoto);
                    $objEntityManager->flush();

                    $response['status'] = self::OK;
                    $response['msg']    = self::IMAGE_UPDATED;
                } else {
                    $response['status'] = self::NOK;
                    $response['msg']    = self::USER_NOT_VALID;
                }

                } catch (\Exception $e) {
                    $response['status'] = self::NOK;
                    $response['msg']    = 'General error' . $e->getMessage();
                }
            } else {
                $response['status'] = self::NOK;
                $response['msg']    = self::ERR_UPLOAD_IMAGE;
            }
        } else {
            $response['status'] = self::NOK;
            $response['msg']    = self::FIELDS_MADATORIES;
        }
        return $response;
    }

    /**
     * putUserEditPhotosAction Index page
     *
     * @param Request $request ojb Request
     * @param integer $user    user
     * @param integer $photoid photoid
     *
     * @return json
     */
    public function putUserEditPhotosAction(Request $request, $user, $photoid)
    {
        $response  = array();
        $public    = $request->get('public');
        $objFile   = $request->files->get('file');
        if ($objFile != null && $public != null) {
            $extension = $objFile->getClientOriginalExtension();
            $path      = $this->container->getParameter('getUserPhotosPath');
            if ($objFile->getError() === 0) {
                try {
                    $objEntityManager  = $this->getDoctrine()->getManager();
                    $objUserPhotoRepository = $objEntityManager->getRepository('AcmeDemoBundle:UserPhoto');
                    $objUserPhoto           = $objUserPhotoRepository->findOneBy(array('id'=>$photoid));
                    if (is_object($objUserPhoto)) {

                        //check Extension:
                        $aryParts = explode('.', $objUserPhoto->getPhoto());
                        $fileNameFull = $aryParts[0] . '.' . $extension;
                        $objFile->move($path . $user, $fileNameFull);
                        $objUserPhoto->setPhoto($fileNameFull);
                        $objUserPhoto->setPublic($public);
                        $objEntityManager->persist($objUserPhoto);
                        $objEntityManager->flush();

                        $response['status'] = self::OK;
                        $response['msg']    = self::IMAGE_UPDATED;
                    } else {
                        $response['status'] = self::NOK;
                        $response['msg']    = self::PHOTO_INVALID;
                    }
                } catch (\Exception $e) {
                    $response['status'] = self::NOK;
                    $response['msg']    = 'General error' . $e->getMessage();
                }
            } else {
                $response['status'] = self::NOK;
                $response['msg']    = self::ERR_UPLOAD_IMAGE;
            }
        } else {
            $response['status'] = self::NOK;
            $response['msg']    = self::FIELDS_MADATORIES;
        }
        return $response;
    }

    /**
     * deleteUserPhotosAction Index page
     *
     * @param Request $request ojb Request
     * @param integer $user    user
     * @param integer $photoid photoid
     *
     * @return json
     */
    public function deleteUserPhotosAction(Request $request, $user, $photoid)
    {
        $objEntityManager       = $this->getDoctrine()->getManager();
        $objUserPhotoRepository = $objEntityManager->getRepository('AcmeDemoBundle:UserPhoto');
        $ojbUserPhoto           = $objUserPhotoRepository->findOneBy(array('id'=>$photoid));
        if ($ojbUserPhoto) {
            if ((int) $user === (int) $ojbUserPhoto->getUserId()) {
                if (file_exists($ojbUserPhoto->getPhoto())) {
                    unlink($ojbUserPhoto->getPhoto());
                }
                $objEntityManager->remove($ojbUserPhoto);
                $objEntityManager->flush();
                $response['status'] = self::OK;
                $response['msg']    = self::IMAGE_DELETED;
            } else {
                $response['status'] = self::NOK;
                $response['msg']    = self::IMAGE_USER;
            }
        } else {
            $response['status'] = self::NOK;
            $response['msg']    = self::IMAGE_NOT_FOUND;
        }
        return $response;
    }

    /**
     * postUserVisitsAction Index page
     *
     * @param Request $request ojb Request
     * @param integer $user    user
     *
     * @return json
     */
    public function postUserVisitsAction(Request $request, $user)
    {
        $objEntityManager  = $this->getDoctrine()->getManager();
        $objUserRepository = $objEntityManager->getRepository('AcmeDemoBundle:User');
        $ojbUser           = $objUserRepository->findOneBy(array('id'=>$user));

        if (is_object($ojbUser)) {
            $form = $this->getForm();
            $form->bind($request);
            //$form->handleRequest($request);
            $data = $form->getData();

            if ($data['city'] !==''  && $data['state'] !== '') {

                $objCityRepository = $objEntityManager->getRepository('AcmeDemoBundle:City');
                $objCity            = $objCityRepository->findOneBy(array('name'=>$data['city'], 'state' => $data['state']));

                $objUserCityRepository = $objEntityManager->getRepository('AcmeDemoBundle:UserCity');
                $ojUserCity            = $objUserCityRepository->findOneBy(array('userId'=>$ojbUser->getId(), 'cityId' => $objCity->getId()));

                $response = array();

                if (is_object($ojUserCity)) {
                    $response['status'] = self::OK;
                    $response['msg']    = self::USER_CITY_EXISTS;
                } else {
                    $objUserCity = new UserCity();
                    $objUserCity->setUserId($ojbUser->getId());
                    $objUserCity->setCityId($objCity->getId());
                    $objEntityManager->persist($objUserCity);
                    $objEntityManager->flush();
                    $response['status'] = self::OK;
                    $response['msg']    = self::USER_CITY_INSERTED;
                }

                return $response;
            } else {
                throw $this->createNotFoundException();
            }
        } else {
            throw $this->createNotFoundException();
        }
    }
}