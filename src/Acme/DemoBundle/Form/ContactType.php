<?php
/**
 * ContactType
 *
 * @category   PHP5
 * @package    Acme
 * @subpackage Form
 * @author     Tairik Jean da Costa <tairik@hotmail.com>
 * @license    http://www.tairik.com/license/ BSD Licence
 * @link       https://tairik@bitbucket.org/tairik/simplerest.git
 */
namespace Acme\DemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
/**
 * This class catches the controller and calls the appropriate action requested.
 * It manages everything related to the home page, etc.
 *
 * @category   PHP5
 * @package    Acme
 * @subpackage Form
 * @author     Tairik Jean da Costa <tairik@hotmail.com>
 * @license    http://www.tairik.com/license/ BSD Licence
 * @link       https://tairik@bitbucket.org/tairik/simplerest.git
 */

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email');
        $builder->add('message', 'textarea');
    }

    public function getName()
    {
        return 'contact';
    }
}